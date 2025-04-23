<?php
namespace App\Http\Livewire;

use App\Models\Training;
use Livewire\Component;
use Livewire\WithPagination;

class TrainerSchedule extends Component
{
    use WithPagination;

    public $date;
    public $trainerId;
    public $confirmationModal = false;
    public $cancelReason = '';
    public $trainingIdToCancel = null;

    public function mount($trainerId)
    {
        $this->trainerId = $trainerId;
    }

    public function render()
    {
        $query = Training::with(['clients'])
            ->where('trainer_id', $this->trainerId);

        if ($this->date) {
            $query->whereDate('training_at', $this->date);
        }

        return view('livewire.trainer-schedule', [
            'trainings' => $query->orderBy('training_at')->paginate(10),
        ]);
    }

    // Метод для изменения статуса тренировки на "проведена"
    public function markAsCompleted($trainingId)
    {
        $training = Training::find($trainingId);
        $training->status = 'completed'; // Обновляем статус на "проведена"
        $training->save();
    }

    // Метод для отображения модалки для отмены тренировки
    public function showCancelModal($trainingId)
    {
        $this->trainingIdToCancel = $trainingId;
        $this->confirmationModal = true;
    }

    // Метод для отмены тренировки
    public function cancelTraining()
    {
        $training = Training::find($this->trainingIdToCancel);
        $training->status = 'cancelled'; // Обновляем статус на "отменена"
        $training->cancel_reason = $this->cancelReason; // Добавляем причину отмены
        $training->save();

        $this->confirmationModal = false; // Закрыть модалку
        $this->cancelReason = ''; // Очистить комментарий
        $this->trainingIdToCancel = null; // Очистить id
    }
}
