<?php

namespace App\Http\Livewire\Admin\Users;

use App\Exports\UsersExport;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\withPagination;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class UserTableOld extends Component
{
    public $selectAll = false;
    public Collection $selectedItems;
    public $role='';
    public $status='';
    public $user_id;
    public $searchStr='';
    private $usersList ;
    protected $listeners = ['deleteConfirmed' => 'deleteRecord'];

    use withPagination;

    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $users = $this->getUsersBuilder();
        $this->usersList = $users->paginate(10);
        return view('livewire.admin.users.user-table',['users'=>$this->usersList]);
    }

    public function mount()
    {
        $this->usersList = $this->getUsersBuilder()->paginate(10);
        $this->selectedItems = collect();
    }

    //------ Event for Select All Changed
    public function updatedSelectAll($value)
    {
        if($value)
            $this->selectedItems = User::pluck('id');
        else
            $this->selectedItems = collect();
    }

    //------ Event for Role_Changed
    public function updatedRole(){
    }

    public function updatedStatus(){
    }

    private function getSelectedUsers(): Collection
    {
        return $this->selectedItems->filter(fn($p)=>$p)->keys();
    }

    public function export_print()
    {
        $users = $this->getUsersBuilder();
        $m = $users
            ->get()
            ->collect()
            ->map(function ($user){
                return $user->id;
            });
        return redirect()->route('admin.users.export',$m);
    }
    public function export_excel(): BinaryFileResponse
    {
        return Excel::download( new UsersExport($this->getSelectedUsers()),'excelfile.xlsx',);
    }

    public function export_pdf(): RedirectResponse
    {
        //$pdf = Pdf::loadView('livewire.admin.users.export.users-table-pdf',$this->getSelectedUsers());
        return redirect()->route('admin.users.export',$this->getSelectedUsers()->collect());
    }

    //------ Show Message to confirm delete
    public function confirm_deleteUser($user_id)
    {
        $this->user_id = $user_id;
        $this->dispatchBrowserEvent('confirm_delete');
    }

    //------ Delete record action
    public function deleteRecord()
    {
        $this->dispatchBrowserEvent('record_deleted');
    }

    /**
     * @return Builder
     */
    private function getUsersBuilder(): Builder
    {
        return User::query()
            ->leftJoin('roles', 'roles.user_id', '=', 'users.id')
            ->where(function ($query) {
                if ($this->role != '')
                    $query->where('roles.title', '=', $this->role);
                if ($this->status != '')
                    $query->where('users.status', '=', $this->status);
                if ($this->searchStr != '')
                    $query->where('users.name', 'like', '%' . $this->searchStr . '%');
            });
    }

}
