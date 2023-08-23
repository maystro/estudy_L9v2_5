<?php

namespace App\Http\Livewire\Admin\Levels;

use App\Models\Level;
use Livewire\Component;

class LevelForm extends Component
{
    public $frm_level_id;
    public $frm_level_title;
    public $form_title='مستوى جديد';
    public $form_mode='create';
    public $auto_index=0;

    private $form_modal='#level-form-modal';
    private $parentLivewirePage='admin.levels.index';

    protected $listeners=[
        'editRecord'=>'editRecord_eventHandler'
    ];

    public function render()
    {
        return view('livewire.admin.levels.level-form');
    }
    public function editRecord_eventHandler($id)
    {
        $level = Level::query()->find($id)->first();
        if($level)
        {
            $this->frm_level_title = $level->title;
            $this->frm_level_id = $level->id;

            $this->form_mode='update';
            $this->form_title='تحديث مستوى : '.$level->title;
        }
    }
    public function save()
    {
        $level = level::query()->find($this->frm_level_id);

        if (!$level)
            $this->createlevel();
        else
            $this->updatelevel($level->id);
    }

    private function validateInputs()
    {
        $this->validate([
            'frm_level_title'=>[
                'required','min:3',
                Rule::unique('levels','title')
                    ->where(fn ($query) =>
                    $query->where([
                            ['title','=', $this->frm_level_title],
                        ]
                    )),
            ],
        ],[
            'frm_level_title.unique'=>'اسم هذه المستوى مستخدم من قبل في نفس المستوى',
            'frm_level_title.required'=>'لابد من كتابة عنوان المستوى',
            'frm_level_title.min'=>'عنوان المستوى لا يقل عن ٣ حروف',
        ]);

    }

    private function createlevel()
    {
//        $this->frm_level_title = InputRules::translateCharacters($this->frm_level_title);
        $this->validateInputs();
        $this->auto_index = level::query()->max('idx')+1;
        $level = level::query()->create([
            'title'=>$this->frm_level_title,
            'idx'=>$this->auto_index
        ]);

        $this->editRecord_eventHandler($level->id);
        $this->dispatchBrowserEvent('hideModal',['target'=>$this->form_modal]);
        $this->emitTo('admin.levels.index','refresh');
    }

    private function updatelevel($id)
    {
        $level = level::query()->find($id);

        $this->validateInputs();

        $level->title=$this->frm_level_title;
        $level->save();
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
