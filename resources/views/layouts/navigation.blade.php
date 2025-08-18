@auth
@php
    $unreadCount = auth()->user()->unreadNotifications()->count();
    $last = auth()->user()->notifications()->latest()->limit(8)->get();
@endphp

<div x-data="{open:false}" class="relative" style="z-index:9999">
    {{-- مهم: href يضمن الفتح حتى لو JS واقف --}}
    <a href="{{ route('notifications.index') }}"
       class="relative inline-flex items-center"
       @click.stop.prevent="open = !open">
        <i class="fas fa-bell text-gray-700 text-xl"></i>
        @if($unreadCount > 0)
            <span class="absolute -top-2 -right-2 text-[10px] bg-red-600 text-white rounded-full px-1.5 py-0.5">
                {{ $unreadCount }}
            </span>
        @endif
    </a>

    {{-- القائمة المنسدلة --}}
    <div x-show="open" @click.outside="open=false"
         x-transition
         class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="px-4 py-3 border-b bg-gray-50 font-semibold text-gray-700">
            آخر الإشعارات
        </div>

        <ul class="max-h-72 overflow-auto divide-y">
            @forelse($last as $n)
                @php $data = $n->data; @endphp
                <li class="p-3 {{ $n->read_at ? '' : 'bg-amber-50' }}">
                    <form method="POST" action="{{ route('notifications.read', $n->id) }}" class="block">
                        @csrf
                        <button type="submit" class="text-right w-full">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $data['title'] ?? 'إشعار' }}
                            </div>
                            <div class="text-xs text-gray-600 mt-0.5">
                                @if(($data['type'] ?? '') === 'topup')
                                    نقطة: {{ $data['pos_name'] ?? $data['pos_id'] }} — مبلغ: {{ number_format($data['amount'] ?? 0, 2) }}
                                @elseif(($data['type'] ?? '') === 'card')
                                    مستخدم: {{ $data['username'] ?? '' }} — باقة: {{ $data['package'] ?? '' }} — سعر: {{ number_format($data['price'] ?? 0, 2) }}
                                @else
                                    {{ \Illuminate\Support\Str::limit(json_encode($data, JSON_UNESCAPED_UNICODE), 70) }}
                                @endif
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
            <form method="POST" action="{{ route('notifications.readAll') }}">
                @csrf
                <button class="text-xs text-gray-700 hover:text-gray-900">تعليم الكل كمقروء</button>
            </form>
            <a href="{{ route('notifications.index') }}" class="text-xs text-indigo-600 hover:text-indigo-800">عرض الكل</a>
        </div>
    </div>
</div>
@endauth
