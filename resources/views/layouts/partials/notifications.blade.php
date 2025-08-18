@auth
@php
    $user   = auth()->user();
    $unread = $user->unreadNotifications()->count();
    $latest = $user->notifications()->latest()->limit(8)->get();

    // رابط احتياطي يفتح صفحة الإشعارات حتى لو تعطل JS
    $indexUrl = \Illuminate\Support\Facades\Route::has('notifications.index')
        ? route('notifications.index')
        : url('/notifications');
@endphp

<div x-data="{open:false}" class="relative" style="z-index:1000">
    {{-- الزر/الرابط --}}
    <a href="{{ $indexUrl }}"
       class="relative inline-flex items-center text-gray-300 hover:text-white"
       @click.stop.prevent="open = !open"
       aria-label="الإشعارات"
       aria-expanded="false"
       role="button">
        <i class="fas fa-bell text-xl"></i>
        @if($unread > 0)
            <span class="absolute -top-2 -right-2 text-[10px] bg-red-600 text-white rounded-full px-1.5 py-0.5"
                  aria-label="عدد الإشعارات غير المقروءة">{{ $unread }}</span>
        @endif
    </a>

    {{-- القائمة المنسدلة --}}
    <div x-show="open" @click.outside="open=false" x-transition
         class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="px-4 py-3 border-b bg-gray-50 font-semibold text-gray-700">آخر الإشعارات</div>

        <ul class="max-h-72 overflow-auto divide-y">
            @forelse($latest as $n)
                @php($data = $n->data)
                <li class="p-3 {{ $n->read_at ? '' : 'bg-amber-50' }}">
                    <form method="POST"
                          action="{{ \Illuminate\Support\Facades\Route::has('notifications.read') ? route('notifications.read', $n->id) : $indexUrl }}"
                          class="block">
                        @csrf
                        <button type="submit" class="text-right w-full">
                            <div class="text-sm font-medium text-gray-900">
                                {{ e($data['title'] ?? 'إشعار') }}
                            </div>

                            <div class="text-xs text-gray-600 mt-0.5">
                                @switch($data['type'] ?? null)
                                    @case('topup')
                                        نقطة: {{ e($data['pos_name'] ?? $data['pos_id'] ?? '') }}
                                        — مبلغ: {{ number_format((float)($data['amount'] ?? 0), 2) }}
                                        @break

                                    @case('card')
                                        مستخدم: {{ e($data['username'] ?? '') }}
                                        — باقة: {{ e($data['package'] ?? '') }}
                                        — سعر: {{ number_format((float)($data['price'] ?? 0), 2) }}
                                        @break

                                    @case('pos_low_balance')
                                        {{ e($data['pos_name'] ?? 'نقطة') }}
                                        — الرصيد: {{ number_format((float)($data['balance'] ?? 0), 2) }}
                                        (الحد: {{ number_format((float)($data['threshold'] ?? 0), 0) }})
                                        @break

                                    @default
                                        {{ \Illuminate\Support\Str::limit(json_encode($data, JSON_UNESCAPED_UNICODE), 80) }}
                                @endswitch
                            </div>

                            <div class="text-[11px] text-gray-400 mt-1">
                                {{ optional($n->created_at)->diffForHumans() }}
                            </div>
                        </button>
                    </form>
                </li>
            @empty
                <li class="p-4 text-sm text-gray-500">لا توجد إشعارات</li>
            @endforelse
        </ul>

        <div class="flex items-center justify-between px-3 py-2 border-t bg-gray-50">
            <form method="POST"
                  action="{{ \Illuminate\Support\Facades\Route::has('notifications.readAll') ? route('notifications.readAll') : $indexUrl }}">
                @csrf
                <button class="text-xs text-gray-700 hover:text-gray-900">تعليم الكل كمقروء</button>
            </form>

            <a href="{{ $indexUrl }}" class="text-xs text-indigo-600 hover:text-indigo-800">عرض الكل</a>
        </div>
    </div>
</div>
@endauth
