{{-- resources/views/admin/components/modals/delete-modals-rewards.blade.php --}}
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">{{ __("Confirm Deletion") }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="deleteModalMessage">{{ __("Are you sure you want to delete this reward?") }}</p>
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __("Cancel") }}</button>
                    <button type="submit" class="btn btn-danger">{{ __("Delete") }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
