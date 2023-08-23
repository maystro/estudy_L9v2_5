<?php

namespace App\Http\Livewire\Admin\Subjects;

use App\Components\InputRules;
use App\Models\Level;
use App\Models\Subject;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Form extends Component
{
    //form variables
    public $frm_subject_id;
    public $frm_subject_title;
    public $frm_subject_min=50;
    public $frm_subject_max=100;
    public $form_title='مادة جديدة';
    public $form_mode='create';
    public $auto_index=0;

    private $form_modal='#subject-form-modal';
    private $parentLivewirePage='admin.subject.index';

    protected $listeners=[
        'editRecord'=>'editRecord_eventHandler'
    ];


    public function render()
    {
        return view('livewire.admin.subjects.form');
    }

    public function editRecord_eventHandler($id)
    {
        $subject = Subject::query()->find($id)->first();
        if($subject)
        {
            $this->frm_subject_title = $subject->title;
            $this->frm_subject_id = $subject->id;
//            $this->frm_subject_min = $subject->min;
//            $this->frm_subject_max = $subject->max;

            $this->form_mode='update';
            $this->form_title='تحديث مادة : '.$subject->title;
        }
    }
    public function save()
    {
        $subject = Subject::query()->find($this->frm_subject_id);

        if (!$subject)
            $this->createSubject();
        else
            $this->updateSubject($subject->id);
    }

    private function validateInputs()
    {
        $this->validate([
            'frm_subject_title'=>[
                'required','min:3',
                Rule::unique('subjects','title')
                    ->where(fn ($query) =>
                    $query->where([
                            ['title','=', $this->frm_subject_title],
                            ]
                    )),
            ],
            'frm_subject_min'=>'numeric|gt:0,lt:100',
            'frm_subject_max'=>'numeric|gt:0,lt:100',
        ],[
            'frm_subject_title.unique'=>'اسم هذه المادة مستخدم من قبل في نفس المستوى',
            'frm_subject_title.required'=>'لابد من كتابة عنوان المادة',
            'frm_subject_title.min'=>'عنوان المادة لا يقل عن ٣ حروف',
            'frm_subject_min.numeric'=>'أرقام فقط',
            'frm_subject_max.numeric'=>'أرقام فقط',
        ]);

    }

    private function createSubject()
    {
//        $this->frm_subject_title = InputRules::translateCharacters($this->frm_subject_title);
        $this->validateInputs();
        $this->auto_index = Subject::query()->max('idx')+1;
        $subject = Subject::query()->create([
            'title'=>$this->frm_subject_title,
//            'min'=>$this->frm_subject_min,
//            'max'=>$this->frm_subject_max,
            'idx'=>$this->auto_index
        ]);

        $this->editRecord_eventHandler($subject->id);
        $this->dispatchBrowserEvent('hideModal',['target'=>$this->form_modal]);
        $this->emitTo('admin.subjects.index','refresh');
    }

    private function updateSubject($id)
    {
        $subject = Subject::query()->find($id);

//        $this->frm_subject_title = InputRules::translateCharacters($this->frm_subject_title);
        $this->validateInputs();

        $subject->title=$this->frm_subject_title;
//        $subject->min=$this->frm_subject_min;
//        $subject->max=$this->frm_subject_max;
        $subject->save();
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
