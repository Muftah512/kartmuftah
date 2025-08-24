@extends('layouts.pos')

@section('title', 'تفاصيل الكرت')

@section('content')
@php
    use App\Models\SystemSetting;

    // تحميل العلاقات الضرورية
    $card->loadMissing(['package','pos']);

    // تجهيز بيانات العرض
    $posName = $card->pos->name
        ?? optional(auth()->user()->pointOfSale)->name
        ?? 'غير محدد';

    $price = $card->price ?? optional($card->package)->price ?? 0;
    $days  = optional($card->package)->validity_days;
    $expiry= $card->expiration_date?->format('d/m/Y');

    $company  = SystemSetting::getValue('company_name', 'المفتاح');
    $support  = SystemSetting::getValue('support_phone', '773377968');
    $cc       = SystemSetting::getValue('default_country_code', '967'); // رمز الدولة الافتراضي

    // === نص المشاركة النصي (بدون أي روابط) ===
    $lines = [];
    $lines[] = "كرت- {$company}";
    $lines[] = "👤 اسم المستخدم: {$card->username}";
    if (optional($card->package)->name) { $lines[] = "📦 باقة: ".$card->package->name; }
    //$lines[] = "💰 السعر: ".number_format($price)." ريال يمني";
   // if ($days)  { $lines[] = "⏳ مدة الصلاحية: {$days} يوم"; }
    //if ($expiry){ $lines[] = "📅 تاريخ الانتهاء: {$expiry}"; }
   // $lines[] = "شكراً لاستخدامكم نظام كرت المفتاح";
    //$lines[] = "للتواصل: {$support}";
    $shareText = implode("\n", $lines);
    $shareTextEnc = rawurlencode($shareText);

    // === نص رسالة واتساب مع رقم عميل إن وُجد (اختياري) ===
    $waText = $shareText; // نفس النص
    $waTextEnc = rawurlencode($waText);

    // تجهيز رقم العميل إن كان محفوظاً (تحويله للصيغة الدولية)
    $waUrl = null;
    if ($card->customer_phone) {
        $clean = preg_replace('/\D/', '', $card->customer_phone);
        if ($clean) {
            if (substr($clean, 0, strlen($cc)) !== $cc) {
                $clean = $cc . ltrim($clean, '0');
            }
            $waUrl = "https://wa.me/{$clean}?text={$waTextEnc}";
        }
    }

    // خريطة الحالات
    $statusMap = [
        'pending'   => ['label' => 'قيد التجهيز', 'class' => 'text-orange-600'],
        'active'    => ['label' => 'نشط',        'class' => 'text-green-600'],
        'failed'    => ['label' => 'فشل',        'class' => 'text-red-600'],
        'expired'   => ['label' => 'منتهٍ',      'class' => 'text-gray-600'],
        'recharged' => ['label' => 'معاد شحنه',  'class' => 'text-blue-600'],
    ];
    $statusInfo = $statusMap[$card->status] ?? ['label' => $card->status, 'class' => 'text-gray-800'];
@endphp

<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-purple-600 to-indigo-700 px-6 py-4">
                <div class="flex flex-wrap justify-between items-center gap-3">
                    <h1 class="text-2xl font-bold text-white">تفاصيل الكرت</h1>
                    <div class="flex flex-wrap items-center gap-2">
                        {{-- مشاركة نصية (Share Sheet للنظام) --}}
                        <button id="btn-native-share"
                                class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-lg">
                            مشاركة نصياً
                        </button>

                        {{-- واتساب بدون رقم (نصي فقط) --}}
                        <a id="btn-wa-generic"
                           href="#"
                           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                            واتساب (نص فقط)
                        </a>

                        {{-- SMS --}}
                        <a id="btn-sms"
                           href="#"
                           class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded-lg">
                            SMS
                        </a>

                        {{-- تيليغرام --}}
                        <a id="btn-telegram"
                           href="https://t.me/share/url?text={{ $shareTextEnc }}"
                           target="_blank" rel="noopener"
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                            تيليغرام
                        </a>

                        {{-- نسخ --}}
                        <button id="btn-copy"
                                class="bg-gray-200 hover:bg-gray-300 text-gray-900 px-4 py-2 rounded-lg">
                            نسخ التفاصيل
                        </button>

                        {{-- واتساب مع رقم العميل إن وجد --}}
                        @if($waUrl)
                            <a href="{{ $waUrl }}" target="_blank" rel="noopener"
                               class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                                إرسال لرقم العميل
                            </a>
                        @endif

                        {{-- طباعة --}}
                        <button onclick="window.print()"
                                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                            طباعة
                        </button>
                    </div>
                </div>
            </div>

            <!-- Body -->
            <div class="p-6 print-content">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">معلومات الكرت</h2>
                        <p class="text-gray-600">{{ now()->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="bg-gray-100 rounded-lg p-3">
                        <div class="text-center">
                            <div class="text-xs text-gray-500">نقطة البيع</div>
                            <div class="font-bold text-gray-900">{{ $posName }}</div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-xl p-6 mb-6 border border-gray-200">
                    <div class="text-center mb-4">
                        <div class="text-sm text-gray-600">اسم المستخدم</div>
                        <div class="text-3xl font-bold text-gray-900 tracking-wider">{{ $card->username }}</div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="bg-white p-4 rounded-lg border border-gray-200">
                            <div class="text-sm text-gray-600">الباقة</div>
                            <div class="font-bold text-gray-900">{{ $card->package->name ?? 'غير محدد' }}</div>
                        </div>

                        <div class="bg-white p-4 rounded-lg border border-gray-200">
                            <div class="text-sm text-gray-600">السعر</div>
                            <div class="font-bold text-gray-900">{{ number_format($price) }} ريال</div>
                        </div>

                        <div class="bg-white p-4 rounded-lg border border-gray-200">
                            <div class="text-sm text-gray-600">تاريخ الانتهاء</div>
                            <div class="font-bold text-gray-900">
                                {{ $card->expiration_date ? $card->expiration_date->format('d/m/Y H:i') : 'غير محدد' }}
                            </div>
                        </div>

                        <div class="bg-white p-4 rounded-lg border border-gray-200">
                            <div class="text-sm text-gray-600">الحالة</div>
                            <div class="font-bold {{ $statusInfo['class'] }}">{{ $statusInfo['label'] }}</div>
                        </div>
                    </div>

                    @if($card->customer_phone)
                        <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-phone text-blue-500"></i>
                                <div>
                                    <div class="text-sm text-gray-600">رقم العميل</div>
                                    <div class="font-bold text-gray-900">
                                        @php
                                            $show = preg_replace('/\D/', '', $card->customer_phone);
                                            if ($show && substr($show, 0, strlen($cc)) !== $cc) {
                                                $show = $cc . ltrim($show, '0');
                                            }
                                        @endphp
                                        +{{ $show }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="text-center text-sm text-gray-600 mt-6">
                        شكراً لاستخدامكم نظام <span class="font-semibold text-gray-900">كرت المفتاح</span>
                    </div>
                </div>

                <div class="flex justify-between">
                    <a href="{{ route('pos.dashboard') }}"
                       class="bg-gray-200 hover:bg-gray-300 text-gray-900 font-bold py-2 px-6 rounded-lg transition duration-300 flex items-center">
                        العودة للرئيسية
                        <svg class="w-5 h-5 ms-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                             xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>

                    {{-- زر فتح المودال عند عدم وجود رقم --}}
                    @if(!$waUrl)
                        <button id="whatsappBtn"
                                class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-6 rounded-lg transition duration-300">
                            إرسال لرقم محدد (واتساب)
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- مودال إدخال رقم الواتساب عند عدم وجوده --}}
    @if(!$waUrl)
        <div id="whatsappModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 items-center justify-center">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-md overflow-hidden">
                <div class="bg-gradient-to-r from-green-600 to-teal-700 px-6 py-4">
                    <h2 class="text-xl font-bold text-white">إرسال عبر واتساب</h2>
                </div>

                <div class="p-6">
                    <div class="mb-6">
                        <label for="whatsapp_phone" class="block text-gray-900 font-medium mb-2">رقم هاتف العميل</label>
                        <div class="flex">
                            <span class="inline-flex items-center px-3 rounded-s-md border border-e-0 border-gray-300 bg-gray-50 text-gray-600">
                                +{{ $cc }}
                            </span>
                            <input type="text" id="whatsapp_phone"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-e-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                   placeholder="7xxxxxxxx" pattern="[0-9]{8,12}" required>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">سيتم فتح واتساب وإدراج الرسالة مباشرةً.</p>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" id="cancelWhatsapp"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-900 font-bold py-2 px-6 rounded-lg">
                            إلغاء
                        </button>
                        <button type="button" id="sendWhatsapp"
                                class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg">
                            إرسال
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
    @media print {
        body * { visibility: hidden; }
        .print-content, .print-content * { visibility: visible; }
        .print-content { position: absolute; inset-inline-start: 0; inset-block-start: 0; width: 100%; }
        .no-print { display: none !important; }
    }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // === عناصر المشاركة العامة ===
    const shareText = @json($shareText);

    // Native Share (اختيار التطبيق من النظام)
    const nativeBtn = document.getElementById('btn-native-share');
    if (nativeBtn) {
        nativeBtn.addEventListener('click', async () => {
            if (navigator.share) {
                try { await navigator.share({ title: 'تفاصيل الكرت', text: shareText }); }
                catch (_) { /* المستخدم ألغى */ }
            } else {
                alert('المشاركة الأصلية غير مدعومة على هذا المتصفح.');
            }
        });
    }

    // واتساب (نص فقط) بدون رقم
    const waGeneric = document.getElementById('btn-wa-generic');
    if (waGeneric) {
        const isMobile = /Android|iPhone|iPad|iPod/i.test(navigator.userAgent);
        const waDeep  = 'whatsapp://send?text=' + encodeURIComponent(shareText); // للجوال
        const waWeb   = 'https://wa.me/?text=' + encodeURIComponent(shareText);  // للويب
        waGeneric.setAttribute('href', isMobile ? waDeep : waWeb);
        if (!isMobile) waGeneric.setAttribute('target', '_blank');
        waGeneric.setAttribute('rel', 'noopener');
    }

    // SMS: iOS يستخدم &body ، وأندرويد ?body
    const smsBtn = document.getElementById('btn-sms');
    if (smsBtn) {
        const isIOS  = /iPad|iPhone|iPod/.test(navigator.userAgent);
        const smsHref = isIOS ? 'sms:&body=' + encodeURIComponent(shareText)
                              : 'sms:?body=' + encodeURIComponent(shareText);
        smsBtn.setAttribute('href', smsHref);
    }

    // نسخ للحافظة
    const copyBtn = document.getElementById('btn-copy');
    if (copyBtn) {
        copyBtn.addEventListener('click', async () => {
            try {
                await navigator.clipboard.writeText(shareText);
                alert('تم نسخ تفاصيل الكرت ✅');
            } catch {
                const ta = document.createElement('textarea');
                ta.value = shareText;
                document.body.appendChild(ta);
                ta.select();
                try { document.execCommand('copy'); alert('تم نسخ تفاصيل الكرت ✅'); }
                finally { document.body.removeChild(ta); }
            }
        });
    }

    // === منطق المودال لإرسال واتساب لرقم معيّن (كما كان لديك) ===
    const modal    = document.getElementById('whatsappModal');
    const openBtn  = document.getElementById('whatsappBtn');
    const closeBtn = document.getElementById('cancelWhatsapp');
    const sendBtn  = document.getElementById('sendWhatsapp');
    const input    = document.getElementById('whatsapp_phone');

    const waMsg    = @json($waText);

    if (openBtn && modal) {
        openBtn.addEventListener('click', () => {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            input && input.focus();
        });
    }
    if (closeBtn && modal) {
        closeBtn.addEventListener('click', () => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        });
    }
    window.addEventListener('click', (e) => {
        if (modal && e.target === modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    });

    function buildMsisdn(local, cc) {
        let digits = (local || '').replace(/\D/g, '');
        if (!digits) return null;
        if (!digits.startsWith(cc)) {
            digits = cc + digits.replace(/^0+/, '');
        }
        return digits;
    }

    function openWhatsApp(msisdn, message) {
        const url = `https://wa.me/${msisdn}?text=${encodeURIComponent(message)}`;
        window.open(url, '_blank', 'noopener');
    }

    if (sendBtn && input) {
        sendBtn.addEventListener('click', () => {
            const cc   = @json($cc);
            const msisdn = buildMsisdn(input.value, cc);
            if (!msisdn) return alert('الرجاء إدخال رقم صحيح.');
            openWhatsApp(msisdn, waMsg);
        });
    }
});
</script>
@endpush
@endsection
