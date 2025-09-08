@php
    $user = Auth::user();
    $p = $user?->payments()->latest('id')->first();
    $merchantName = (string) config('app.name', 'ELAN BRANDS');
    $gateway = 'Selcom Pay';
    $merchantId = (string) (config('services.selcom.merchant_id') ?? config('services.selcom.merchant') ?? '—');
    $amount = $p?->amount;
    $currency = $p?->currency ?: 'TZS';
    $ref = $p?->meta['order_id'] ?? $p?->reference ?? $p?->provider_reference ?? '—';
    $transId = $p?->provider_reference ?? ($p?->meta['transaction_id'] ?? '—');
    $channel = strtoupper((string) ($p?->method ?? ($p?->meta['channel'] ?? '—')));
    $from = $p?->phone ?: ($p?->meta['phone_e164'] ?? '—');
    $paidAt = $p?->paid_at ?: $p?->updated_at ?: $p?->created_at;
    $paidAtStr = $paidAt ? $paidAt->format('d/m/Y h:i:s A') : '—';
@endphp

<div class="bg-white shadow sm:rounded-lg border border-gray-200 overflow-hidden">
  <div class="px-4 py-3 border-b flex items-center justify-between">
    <h3 class="text-base font-semibold text-gray-900">Payment Receipt</h3>
    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ ($p && ($p->paid_at || ($p->status === 'success'))) ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
      {{ ($p && ($p->paid_at || ($p->status === 'success'))) ? 'PAID' : strtoupper((string) ($p?->status ?? 'PENDING')) }}
    </span>
  </div>
  <div class="p-4">
    @if($p)
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-2 text-sm">
        <div class="text-gray-500">Service</div>
        <div class="font-medium text-gray-900">{{ $gateway }}</div>

        <div class="text-gray-500">Merchant</div>
        <div class="font-medium text-gray-900">{{ $merchantName }}</div>

        <div class="text-gray-500">Merchant#</div>
        <div class="font-medium text-gray-900">{{ $merchantId }}</div>

        <div class="text-gray-500">Amount</div>
        <div class="font-medium text-gray-900">{{ $currency }} {{ is_numeric($amount) ? number_format($amount, 2, '.', ',') : '—' }}</div>

        <div class="text-gray-500">TransID</div>
        <div class="font-medium text-gray-900">{{ $transId }}</div>

        <div class="text-gray-500">Ref</div>
        <div class="font-medium text-gray-900">{{ $ref }}</div>

        <div class="text-gray-500">Channel</div>
        <div class="font-medium text-gray-900">{{ $channel }}</div>

        <div class="text-gray-500">From</div>
        <div class="font-medium text-gray-900">{{ $from }}</div>

        <div class="text-gray-500">Date</div>
        <div class="font-medium text-gray-900">{{ $paidAtStr }}</div>
      </div>
    @else
      <div class="text-sm text-gray-600">Hakuna malipo yaliyopatikana kwa akaunti hii bado.</div>
    @endif
  </div>
</div>
