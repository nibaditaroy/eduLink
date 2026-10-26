@extends('layouts.app')

@section('title', 'Secure Checkout: ' . $course->title . ' - eduLink')

@section('content')
<div class="bg-slate-100/60 border-b border-slate-200 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">Checkout & Instant Enrollment</span>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mt-1">Complete Your Course Admission</h1>
    </div>
</div>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

        <!-- Left: Payment Options & Billing Information -->
        <div class="lg:col-span-2 space-y-6">
            @if($isFree)
                <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm text-center">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto mb-4 text-2xl">
                        <i class="fa-solid fa-gift"></i>
                    </div>
                    <h2 class="text-xl font-bold text-slate-900 mb-2">Free Course Enrollment</h2>
                    <p class="text-xs text-slate-500 max-w-md mx-auto mb-6">
                        This track is sponsored by eduLink. You do not need to pay any tuition fee. Click below to begin your learning journey immediately.
                    </p>

                    <form action="{{ route('checkout.free', $course->slug) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-8 py-3.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold shadow-lg shadow-emerald-600/25 transition">
                            <i class="fa-solid fa-circle-play mr-1.5"></i> Confirm Free Enrollment & Start Learning
                        </button>
                    </form>
                </div>
            @else
                <form action="{{ route('checkout.process', $course->slug) }}" method="POST" id="checkout-form">
                    @csrf
                    <!-- Payment Methods Tabs -->
                    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm mb-6">
                        <h2 class="text-base font-bold text-slate-900 mb-2 flex items-center gap-2">
                            <i class="fa-solid fa-shield-halved text-indigo-600"></i> Select Payment Channel (Bangladesh)
                        </h2>
                        <p class="text-xs text-slate-500 mb-6">Choose your preferred mobile financial service or bank card in Bangladeshi Taka (৳ BDT).</p>

                        <!-- Radio Grid of Payment Providers -->
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
                            <!-- bKash -->
                            <label class="payment-method-card cursor-pointer border-2 border-indigo-600 bg-indigo-50/40 p-4 rounded-2xl flex flex-col items-center justify-center text-center transition hover:border-indigo-600">
                                <input type="radio" name="payment_method" value="bkash" checked class="hidden" onchange="switchMethod('bkash')">
                                <div class="w-10 h-10 rounded-xl bg-pink-100 text-pink-600 flex items-center justify-center text-lg mb-2 font-black">
                                    bK
                                </div>
                                <span class="text-xs font-bold text-slate-900">bKash</span>
                                <span class="text-[10px] text-pink-600 font-semibold">Fast MFS</span>
                            </label>

                            <!-- Nagad -->
                            <label class="payment-method-card cursor-pointer border-2 border-slate-200 bg-white p-4 rounded-2xl flex flex-col items-center justify-center text-center transition hover:border-slate-300">
                                <input type="radio" name="payment_method" value="nagad" class="hidden" onchange="switchMethod('nagad')">
                                <div class="w-10 h-10 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center text-lg mb-2 font-black">
                                    ন
                                </div>
                                <span class="text-xs font-bold text-slate-900">Nagad</span>
                                <span class="text-[10px] text-orange-600 font-semibold">Post Digital</span>
                            </label>

                            <!-- Rocket -->
                            <label class="payment-method-card cursor-pointer border-2 border-slate-200 bg-white p-4 rounded-2xl flex flex-col items-center justify-center text-center transition hover:border-slate-300">
                                <input type="radio" name="payment_method" value="rocket" class="hidden" onchange="switchMethod('rocket')">
                                <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-lg mb-2">
                                    <i class="fa-solid fa-rocket"></i>
                                </div>
                                <span class="text-xs font-bold text-slate-900">Rocket</span>
                                <span class="text-[10px] text-purple-600 font-semibold">DBBL MFS</span>
                            </label>

                            <!-- Cards -->
                            <label class="payment-method-card cursor-pointer border-2 border-slate-200 bg-white p-4 rounded-2xl flex flex-col items-center justify-center text-center transition hover:border-slate-300">
                                <input type="radio" name="payment_method" value="card" class="hidden" onchange="switchMethod('card')">
                                <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-lg mb-2">
                                    <i class="fa-solid fa-credit-card"></i>
                                </div>
                                <span class="text-xs font-bold text-slate-900">Debit / Card</span>
                                <span class="text-[10px] text-blue-600 font-semibold">Visa/Master</span>
                            </label>
                        </div>

                        <!-- Dynamic Provider Details Form -->
                        <div id="mfs-fields" class="p-5 rounded-2xl bg-slate-50 border border-slate-200">
                            <div class="flex items-center justify-between mb-3 text-xs">
                                <span class="font-bold text-slate-800" id="mfs-title">bKash Account Details</span>
                                <span class="text-[11px] text-slate-500 font-semibold">Merchant: <strong class="text-slate-700">01700-112233</strong></span>
                            </div>
                            <label for="wallet_number" class="block text-[11px] font-bold text-slate-600 mb-1">Your Mobile Wallet Number</label>
                            <input type="text" id="wallet_number" name="wallet_number" placeholder="e.g. 017XXXXXXXX" value="{{ Auth::user()->phone ?? '01712345678' }}"
                                class="w-full px-3 py-2 text-xs rounded-xl bg-white border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none">
                            <p class="text-[10px] text-slate-400 mt-2">
                                <i class="fa-solid fa-circle-info mr-1"></i> (Simulation Mode): In this environment, transaction confirmation and payment gateway verification happen immediately upon submission.
                            </p>
                        </div>

                        <!-- Card Fields (Initially Hidden) -->
                        <div id="card-fields" class="hidden p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">Card Number</label>
                                <input type="text" placeholder="4242 &bull;&bull;&bull;&bull; &bull;&bull;&bull;&bull; 4242" value="4242 4242 4242 4242"
                                    class="w-full px-3 py-2 text-xs rounded-xl bg-white border border-slate-200 outline-none">
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-600 mb-1">Expiry Date</label>
                                    <input type="text" placeholder="MM/YY" value="12/28" class="w-full px-3 py-2 text-xs rounded-xl bg-white border border-slate-200 outline-none">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-600 mb-1">CVV / CVC</label>
                                    <input type="password" placeholder="123" value="123" class="w-full px-3 py-2 text-xs rounded-xl bg-white border border-slate-200 outline-none">
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Submit Button -->
                    <button type="submit" class="w-full py-4 px-6 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold shadow-xl shadow-indigo-600/30 transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-lock text-xs"></i>
                        <span>Confirm Payment & Access Classroom</span>
                    </button>
                    <p class="text-center text-[10px] text-slate-400 mt-3">
                        <i class="fa-solid fa-shield-check text-emerald-500 mr-1"></i> 256-bit SSL Encrypted &bull; Instant Admission Verification
                    </p>
                </form>
            @endif
        </div>

        <!-- Right: Order Summary Sidebar -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-7">
            <h3 class="text-sm font-bold text-slate-900 mb-4 pb-3 border-b border-slate-100 flex items-center gap-2">
                <i class="fa-solid fa-receipt text-indigo-600"></i> Order Summary
            </h3>

            <!-- Course Snapshot -->
            <div class="flex items-start gap-3 mb-6 pb-6 border-b border-slate-100">
                <div class="w-12 h-12 rounded-xl bg-indigo-900 text-white flex items-center justify-center text-lg flex-shrink-0">
                    <i class="fa-solid fa-code text-cyan-300"></i>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-slate-900 leading-snug line-clamp-2">{{ $course->title }}</h4>
                    <span class="text-[10px] text-slate-400 font-semibold">{{ $course->category }} &bull; {{ ucfirst($course->level) }}</span>
                </div>
            </div>

            <!-- Price Breakdown -->
            <div class="space-y-2 text-xs text-slate-600 mb-6 pb-6 border-b border-slate-100">
                <div class="flex justify-between">
                    <span>Course Tuition</span>
                    <span class="font-bold text-slate-900">
                        @if($isFree) Free @else ৳{{ number_format($course->price, 2) }} Tk @endif
                    </span>
                </div>
                <div class="flex justify-between text-slate-400">
                    <span>Digital Platform Fee (0%)</span>
                    <span>৳0.00 Tk</span>
                </div>
                <div class="flex justify-between text-slate-400">
                    <span>VAT / Taxes</span>
                    <span>৳0.00 Tk</span>
                </div>
                <div class="pt-2 flex justify-between text-sm font-extrabold text-slate-900 border-t border-slate-100">
                    <span>Total Due</span>
                    <span class="text-indigo-600">
                        @if($isFree) Free @else ৳{{ number_format($course->price, 2) }} Tk @endif
                    </span>
                </div>
            </div>

            <!-- Student Profile Review -->
            <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100 text-xs">
                <div class="text-[10px] uppercase font-bold text-slate-400 tracking-wider mb-1">Enrolling Student</div>
                <div class="font-bold text-slate-900">{{ Auth::user()->name }}</div>
                <div class="text-[11px] text-slate-500 truncate">{{ Auth::user()->email }}</div>
            </div>
        </div>

    </div>
</div>

<script>
function switchMethod(method) {
    const cards = document.querySelectorAll('.payment-method-card');
    cards.forEach(c => {
        c.classList.remove('border-indigo-600', 'bg-indigo-50/40');
        c.classList.add('border-slate-200', 'bg-white');
    });

    const selectedRadio = document.querySelector(`input[value="${method}"]`);
    if (selectedRadio) {
        selectedRadio.checked = true;
        selectedRadio.parentElement.classList.add('border-indigo-600', 'bg-indigo-50/40');
        selectedRadio.parentElement.classList.remove('border-slate-200', 'bg-white');
    }

    const mfsFields = document.getElementById('mfs-fields');
    const cardFields = document.getElementById('card-fields');
    const mfsTitle = document.getElementById('mfs-title');

    if (method === 'card') {
        mfsFields.classList.add('hidden');
        cardFields.classList.remove('hidden');
    } else {
        cardFields.classList.add('hidden');
        mfsFields.classList.remove('hidden');
        if (method === 'bkash') {
            mfsTitle.textContent = 'bKash Account Details';
        } else if (method === 'nagad') {
            mfsTitle.textContent = 'Nagad Postal MFS Details';
        } else if (method === 'rocket') {
            mfsTitle.textContent = 'DBBL Rocket Account Details';
        }
    }
}
</script>
@endsection
