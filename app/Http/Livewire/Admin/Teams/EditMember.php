<?php

namespace App\Http\Livewire\Admin\Teams;

use App\Models\Team;
use App\Models\TeamUser;
use Livewire\Component;

class EditMember extends Component
{
    public $form_icon='bxs-group';
    public $form_title='بيانات عضو فريق';
    public $team;
    public $team_member;
    public $role='member';

    public $form_modal='#edit-member-modal-form';
    private $parentLivewirePage='admin.teams.teams-users-manager';


    protected $listeners=['editRecord'=>'editRecord_Handler'];

    public function mount($modalForm)
    {
        //$this->form_modal = $modalForm;
    }
    public function render()
    {
        return view('livewire.admin.teams.edit-member');
    }
    public function editRecord_Handler($id)
    {
        $this->team_member = TeamUser::query()->find($id);
        $this->team = Team::query()->where('id','=',$this->team_member->team_id)->first();
        $this->role = $this->team_member->role;

    }
    public function close()
    {
        $this->reset();
        $this->dispatchBrowserEvent('hideModal',['target'=>$this->form_modal]);
    }
    public function save()
    {

        if($this->team_member)
        {
            $this->team_member->role = $this->role;
            $this->team_member->save();
            $this->dispatchBrowserEvent('hideModal',['target'=>$this->form_modal]);
            $this->dispatchBrowserEvent('show_toast',
                [
                    'message'=>'تم تعديل بيان عضو الفريق بنجاح',
                    'title'=>'تعديل',
                    'class'=>'bg-primary'
                ]);
            $this->emitTo($this->parentLivewirePage,'refresh');
            $this->reset();
        }

    }

}
