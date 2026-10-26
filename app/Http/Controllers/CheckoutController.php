<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /**
     * Show Checkout Page
     */
    public function show($slug)
    {
        $course = Course::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        // Check if user is already enrolled
        if (Auth::check()) {
            $existing = Enrollment::where('user_id', Auth::id())
                ->where('course_id', $course->id)
                ->first();

            if ($existing) {
                return redirect()->route('learn.course', $course->slug)
                    ->with('info', "You are already enrolled in '{$course->title}'. Welcome back to your learning space!");
            }
        }

        // If the course is free, prompt free 1-click enroll
        $isFree = $course->isFree();

        return view('checkout.show', compact('course', 'isFree'));
    }

    /**
     * 1-Click Free Enrollment for Free Courses
     */
    public function freeEnroll(Request $request, $slug)
    {
        $course = Course::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        if (!$course->isFree()) {
            return redirect()->route('checkout.show', $course->slug);
        }

        $userId = Auth::id();

        // Create or activate enrollment
        Enrollment::updateOrCreate(
            ['user_id' => $userId, 'course_id' => $course->id],
            [
                'status' => 'active',
                'progress_percent' => 0,
                'enrolled_at' => now(),
            ]
        );

        return redirect()->route('learn.course', $course->slug)
            ->with('success', "Congratulations! You have enrolled in '{$course->title}' for Free.");
    }

    /**
     * Process Payment & Enroll Student
     */
    public function process(Request $request, $slug)
    {
        $course = Course::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $userId = Auth::id();

        // Check already enrolled
        $existing = Enrollment::where('user_id', $userId)
            ->where('course_id', $course->id)
            ->where('status', 'active')
            ->first();

        if ($existing) {
            return redirect()->route('learn.course', $course->slug)
                ->with('info', "You already have active access to this course.");
        }

        $request->validate([
            'payment_method' => 'required|string|in:bkash,nagad,rocket,card',
            'wallet_number' => 'nullable|string|max:20',
        ]);

        $paymentMethod = $request->payment_method;
        $finalAmount = (float) $course->price;

        // Generate distinctive transaction ID
        $trxId = 'TRX-' . strtoupper($paymentMethod) . '-' . strtoupper(Str::random(8));

        // Simulated gateway response payload
        $gatewayPayload = [
            'status' => 'VALID',
            'tran_id' => $trxId,
            'val_id' => 'VAL-' . Str::random(12),
            'amount' => number_format($finalAmount, 2, '.', ''),
            'currency' => 'BDT',
            'card_type' => strtoupper($paymentMethod) . '-MFS',
            'store_amount' => number_format($finalAmount * 0.985, 2, '.', ''),
            'bank_tran_id' => 'BNK-' . mt_rand(10000000, 99999999),
            'tran_date' => now()->format('Y-m-d H:i:s'),
            'payer_wallet' => $request->wallet_number ?? '017' . mt_rand(10000000, 99999999),
            'payer_name' => Auth::user()->name,
            'payer_email' => Auth::user()->email,
        ];

        // 1. Record Transaction
        $transaction = Transaction::create([
            'user_id' => $userId,
            'course_id' => $course->id,
            'transaction_id' => $trxId,
            'amount' => $finalAmount,
            'currency' => 'BDT',
            'payment_method' => $paymentMethod,
            'card_type' => strtoupper($paymentMethod) . '-MFS',
            'status' => 'completed',
            'gateway_response' => $gatewayPayload,
            'paid_at' => now(),
        ]);

        // 2. Create or Update Enrollment
        Enrollment::updateOrCreate(
            ['user_id' => $userId, 'course_id' => $course->id],
            [
                'transaction_id' => $trxId,
                'status' => 'active',
                'progress_percent' => 0,
                'enrolled_at' => now(),
            ]
        );

        return redirect()->route('learn.course', $course->slug)
            ->with('success', "Payment of ৳" . number_format($finalAmount, 2) . " Tk verified successfully via " . strtoupper($paymentMethod) . "! You now have full lifetime access to '{$course->title}'.");
    }
}
