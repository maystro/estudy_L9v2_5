<?php

namespace App\Http\Livewire\Admin\Teams;

use App\Enums\Roles;
use App\Models\Team;
use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Component;

class TeamForm extends Component
{
    public $teachers=[];

    public $form_team_name='';
    public $form_team_manager='';
    public $form_team_user_id;
    public $form_team_id;

    // Standard Form variables and listeners
    public $form_title='فريق جديد';
    public $form_icon='bxs-group';
    public $form_mode='create';
    public $auto_index=0;
    private $form_modal='#team-form-modal';
    private $parentLivewirePage='admin.teams.teams-manager';

    protected $listeners=[
        'editRecord'=>'editRecord_eventHandler'
    ];


    public function mount()
    {
        $this->teachers = User::query()
            ->join('roles','roles.user_id','=','users.id')
            ->where('roles.title','=',Roles::Teacher)
            ->get([
                'users.id as user_id',
                'users.name AS user_name',
                'roles.id AS role_id',
                'roles.title AS role_title',
            ]);
    }

    public function render()
    {
        return view('livewire.admin.teams.team-form');
    }

    public function updatedFormTeamManager()
    {
    }

    /* Standard Operation for every form */
    /* ================================= */
    public function editRecord_eventHandler($id)
    {
        $team = Team::query()->find($id);
        if($team)
        {
            $this->form_team_id = $team->id;
            $this->form_team_user_id = $team->user_id;
            $this->form_team_name = $team->name;
            $this->form_title='تعديل فريق : '.$team->name;
            $this->form_mode='edit';
        }
    }
    private function validateInputs()
    {
        $this->validate([
            'form_team_name'=>[
                'required','min:3',
                Rule::unique('teams','name')
                    ->ignore($this->form_team_id)
            ],
            'form_team_user_id'=>'required|gt:0'
        ],[
            'form_team_name.unique'=>'اسم الفريق مستخدم من قبل في نفس المستوى',
            'form_team_name.required'=>'لابد من كتابة اسم الفريق',
        ]);

    }
    public function save()
    {
        $record = Team::query()->find($this->form_team_id);
        if (!$record)
            $this->createTeam();
        else
            $this->updateTeam($record);
    }
    private function createTeam()
    {
        $this->validateInputs();
        $record = Team::query()->create([
            'user_id'=>$this->form_team_user_id,
            'name'=>$this->form_team_name,
            'personal_team'=>false,
        ]);
        $this->editRecord_eventHandler($record->id);
        $this->dispatchBrowserEvent('hideModal',['target'=>$this->form_modal]);
        $this->emitTo($this->parentLivewirePage,'refresh');
    }
    private function updateTeam($record)
    {
        $this->validateInputs();

        $record->name = $this->form_team_name;
        $record->user_id = $this->form_team_user_id;
        $record->save();

        $this->dispatchBrowserEvent('hideModal',['target'=>$this->form_modal]);
        $this->emitTo($this->parentLivewirePage,'refresh');
        $this->reset();
    }
    public function close()
    {
        $this->reset();
        $this->dispatchBrowserEvent('hideModal',['target'=>$this->form_modal]);
    }

}
