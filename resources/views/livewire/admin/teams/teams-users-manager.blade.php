<div>
    <x-data-table
        :config="$data"
        createFormModal="{{$this->createFormModal}}"
        editFormModal="{{$this->editFormModal}}"
        :selected-items="$selectedItems"
        :pagesCount="$pagesCount"
        :paginate="$paginate"
        :navMenu="$navMenu"
        :sort-column="$sortColumn"
        :sort-direction="$sortDirection">
    </x-data-table>
</div>

