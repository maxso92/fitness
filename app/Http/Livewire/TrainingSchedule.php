<?php

namespace App\Http\Livewire;

use App\Models\Training;
use Livewire\Component;
use Livewire\WithPagination;

class TrainingSchedule extends BaseComponent
{
    use WithPagination;

    public $title = 'Расписание тренировок';
    public $itemsPerPage = 10;
    public $date;
    public $trainerSearch;
    public $clientSearch;
    public $confirmationModal = false;
    public $cancelReason = '';
    public $trainingIdToCancel = null;
    public $deleteModal = false;
    public $trainingIdToDelete = null;

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function render()
    {
        $query = Training::with(['clients', 'trainer'])
            ->when($this->date, function($query) {
                $query->whereDate('training_at', $this->date);
            })
            ->when($this->trainerSearch, function($query) {
                $query->whereHas('trainer', function($q) {
                    $q->where(function($subQ) {
                        $subQ->where('name', 'like', '%'.$this->trainerSearch.'%')
                            ->orWhere('surname', 'like', '%'.$this->trainerSearch.'%')
                            ->orWhere('patronymic', 'like', '%'.$this->trainerSearch.'%')
                            ->orWhereRaw("CONCAT(surname, ' ', name, ' ', patronymic) LIKE ?", ['%'.$this->trainerSearch.'%'])
                            ->orWhereRaw("CONCAT(surname, ' ', name) LIKE ?", ['%'.$this->trainerSearch.'%']);
                    });
                });
            })
            ->when($this->clientSearch, function($query) {
                $query->whereHas('clients', function($q) {
                    $q->where(function($subQ) {
                        $subQ->where('name', 'like', '%'.$this->clientSearch.'%')
                            ->orWhere('surname', 'like', '%'.$this->clientSearch.'%')
                            ->orWhere('patronymic', 'like', '%'.$this->clientSearch.'%')
                            ->orWhereRaw("CONCAT(surname, ' ', name, ' ', patronymic) LIKE ?", ['%'.$this->clientSearch.'%'])
                            ->orWhereRaw("CONCAT(surname, ' ', name) LIKE ?", ['%'.$this->clientSearch.'%']);
                    });
                });
            });

        $trainings = $query->orderBy('training_at')->paginate($this->itemsPerPage);

        return view('livewire.training-schedule', compact('trainings'));
    }

    public function markAsCompleted($trainingId)
    {
        $training = Training::find($trainingId);
        $training->status = 'completed';
        $training->save();
    }

    public function showCancelModal($trainingId)
    {
        $this->trainingIdToCancel = $trainingId;
        $this->confirmationModal = true;
    }

    public function cancelTraining()
    {
        $training = Training::find($this->trainingIdToCancel);
        $training->status = 'cancelled';
        $training->cancel_reason = $this->cancelReason;
        $training->save();

        $this->confirmationModal = false;
        $this->cancelReason = '';
        $this->trainingIdToCancel = null;
    }


    public function confirmDelete($trainingId)
    {
        $this->trainingIdToDelete = $trainingId;
        $this->deleteModal = true;
    }

    public function deleteTraining()
    {
        try {
            $training = Training::findOrFail($this->trainingIdToDelete);

            if (!$training->canDelete(auth()->user())) {
                throw new \Exception('У вас нет прав для удаления этой тренировки');
            }

            $training->delete();

            $this->emit('showToast', 'success', 'Тренировка успешно удалена');
            $this->emit('refreshComponent');
        } catch (\Exception $e) {
            $this->emit('showToast', 'error', 'Ошибка при удалении: '.$e->getMessage());
        } finally {
            $this->deleteModal = false;
            $this->trainingIdToDelete = null;
        }
    }

}
