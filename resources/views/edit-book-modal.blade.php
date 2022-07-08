<div class="modal editBook" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Book</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <div class="modal-body">
          <form action="{{ route('update.book.details') }}" method="POST" id="update-book-form">
            @csrf
              <input type="hidden" name="bid">
              <div class="form-group">
                  <label for="title">Book Title</label>
                  <input type="text" name="title" class="form-control" id="title" placeholder="Enter book title">
                  <span class="text-danger error-text title_error"></span>
              </div>
              <div class="form-group">
                <label for="author">Author</label>
                <input type="text" name="author" class="form-control" id="author" placeholder="Enter book author">
                <span class="text-danger error-text author_error"></span>
            </div>
            <div class="form-group">
               <button type="submit" class="btn btn-block btn-success">Save Change</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>