<?php
use Livewire\Component;
use App\Infrastructure\Persistence\Models\StockMovement;
use App\Infrastructure\Persistence\Models\Location;
use Livewire\WithPagination;
use App\Infrastructure\Persistence\utils\StockMovementType;
use App\Application\DTOs\Stock\web\StockMovementSearchDto;
use Illuminate\Support\Facades\Log;

new class extends Component {

    use WithPagination;
    public $search = '';
    public $locationId = '';
    public $type = '';
    public $dateFrom = '';
    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'locationId', 'type', 'dateFrom']);
    }

    public function getTypeBadge($type)
    {
        return match ($type) {
            'purchase' => 'success',
            'sale' => 'danger',
            'transfer' => 'warning',
            default => 'info',
        };
    }
    public function with()
    {

        $dto = new StockMovementSearchDto(
            search: $this->search,
            location_id: (int) $this->locationId,
            type: StockMovementType::tryFrom($this->type),
            dataFrom: $this->dateFrom,
        );
        return [
            'stockMovements' => StockMovement::withSearch($dto)->paginate(10),
            'locations' => Location::all(),
        ];
    }
};


?>

<div>
    <x-ui.stock.stock-list :stockMovements="$stockMovements" :locations="$locations" :search="$search"
        :locationId="$locationId" :type="$type" :dateFrom="$dateFrom" :getTypeBadge="fn($type) => $this->getTypeBadge($type)" />
</div>