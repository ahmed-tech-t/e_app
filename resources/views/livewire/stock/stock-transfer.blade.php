<?php
use App\Application\DTOs\TransferProductDto;
use App\Application\Services\LocationService;
use App\Application\Services\ProductService;
use App\Application\Services\StockService;
use App\Domain\Repo\ProductBatchRepo;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    public int $step = 1;
    public ?int $productId = null;
    public ?int $fromLocationId = null;
    public ?int $toLocationId = null;
    public int $quantity = 1;
    public int $availableQuantity = 0;

    #[Computed]
    public function products(): mixed
    {
        return app(ProductService::class)->findAll();
    }

    #[Computed]
    public function locations(): mixed
    {
        return app(LocationService::class)->findAll();
    }

    public function updatedProductId(): void
    {
        $this->fromLocationId = null;
        $this->toLocationId = null;
        $this->availableQuantity = 0;
    }

    public function updatedFromLocationId(): void
    {
        if ($this->productId && $this->fromLocationId) {
            try {
                $batches = app(ProductBatchRepo::class)->getBatchesInLocation($this->productId, $this->fromLocationId);
                $this->availableQuantity = $batches->sum('quantity');
            } catch (\Exception $e) {
                $this->availableQuantity = 0;
            }
        }
    }

    public function goToPreview(): void
    {
        $this->validate([
            'productId' => 'required',
            'fromLocationId' => 'required',
            'toLocationId' => 'required',
            'quantity' => 'required|integer|min:1',
        ]);
        $this->step = 2;
    }

    public function goBack(): void
    {
        $this->step = 1;
    }

    public function transfer(): void
    {
        $dto = new TransferProductDto(
            productId: $this->productId,
            fromLocationId: $this->fromLocationId,
            toLocationId: $this->toLocationId,
            quantity: $this->quantity,
        );
        app(StockService::class)->transferProduct($dto);
        session()->flash('success', 'Stock transferred successfully.');
        $this->redirect(route('stock.index'));
    }
};
?>

<div>
    <div x-show="{{ $step }} === 1">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('app.products.title') }} <span class="text-red-500">*</span></label>
                    <select wire:model.live="productId" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 px-3 py-2 text-sm">
                        <option value="">{{ __('app.stock.select_product') }}</option>
                        @foreach($this->products as $product)
                            <option value="{{ $product->id }}">{{ $product->name_ar }} ({{ $product->code }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('app.stock.from_location') }} <span class="text-red-500">*</span></label>
                    <select wire:model.live="fromLocationId" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 px-3 py-2 text-sm">
                        <option value="">{{ __('app.stock.select_from_location') }}</option>
                        @foreach($this->locations as $location)
                            <option value="{{ $location->id }}">{{ $location->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('app.stock.to_location') }} <span class="text-red-500">*</span></label>
                    <select wire:model="toLocationId" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 px-3 py-2 text-sm">
                        <option value="">{{ __('app.stock.select_to_location') }}</option>
                        @foreach($this->locations as $location)
                            @if($location->id != $this->fromLocationId)
                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('app.common.quantity') }} <span class="text-red-500">*</span></label>
                    <input type="number" wire:model="quantity" min="1" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 px-3 py-2 text-sm">
                    @if($availableQuantity > 0)
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('app.stock.available') }}: {{ $availableQuantity }}</p>
                    @endif
                </div>
            </div>
            <div class="flex justify-end mt-6">
                <button wire:click="goToPreview" class="px-6 py-2 text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700">{{ __('app.stock.preview_transfer') }}</button>
            </div>
        </div>
    </div>

    <div x-show="{{ $step }} === 2">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold mb-6">{{ __('app.stock.transfer_preview') }}</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-center gap-4 py-6 bg-gray-50 dark:bg-gray-900 rounded-lg">
                    <div class="text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('app.stock.from_location') }}</p>
                        @php
                            $fromLoc = collect($this->locations)->first(fn($l) => $l->id == $this->fromLocationId);
                        @endphp
                        <p class="font-semibold">{{ $fromLoc->name ?? '-' }}</p>
                    </div>
                    <svg class="w-8 h-8 text-blue-600 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    <div class="text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('app.stock.to_location') }}</p>
                        @php
                            $toLoc = collect($this->locations)->first(fn($l) => $l->id == $this->toLocationId);
                        @endphp
                        <p class="font-semibold">{{ $toLoc->name ?? '-' }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('app.products.title') }}</p>
                        @php
                            $prod = collect($this->products)->first(fn($p) => $p->id == $this->productId);
                        @endphp
                        <p class="font-medium">{{ ($prod->name_ar ?? '') . ' (' . ($prod->code ?? '') . ')' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('app.common.quantity') }}</p>
                        <p class="font-bold text-lg">{{ $quantity }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('app.stock.available_quantity') }}</p>
                        <p class="font-medium">{{ $availableQuantity }}</p>
                    </div>
                </div>
            </div>
            <div class="flex justify-between mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                <button wire:click="goBack" class="px-4 py-2 text-sm font-medium rounded-lg text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 dark:text-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:hover:bg-gray-600">{{ __('app.common.back') }}</button>
                <button wire:click="transfer" class="px-6 py-2 text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700">{{ __('app.stock.confirm_transfer') }}</button>
            </div>
        </div>
    </div>
</div>
