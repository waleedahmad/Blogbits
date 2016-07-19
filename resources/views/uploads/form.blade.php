<div class="modal fade photoUploadModal" tabindex="-1" role="dialog" aria-labelledby="photoUploadModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <form action="/upload/photo" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Photo Upload</h4>
                </div>
                <div class="modal-body">

                        {{csrf_field()}}
                        <div class="form-group">
                            <label for="photo">File input</label>
                            <input type="file" id="photo" name="photo" required>
                            <p class="help-block">jpeg/png (Max size: 3MB).</p>
                        </div>

                        <div class="form-group">
                            <label for="title">Post Title (max. 120)</label>
                            <textarea class="form-control" rows="3" maxlength="120" name="title" required></textarea>
                        </div>

                        <div class="form-group">
                            <label># Tags (max. 3)</label>
                            <input type="text" class="form-control" name="tags" id="tags" data-role="tagsinput" required>
                        </div>

                        <div class="form-group">
                            <label for="category">Category</label>
                            <select class="form-control" name=category id="category" required>
                                <option value="">Choose Category</option>
                                <option value="fashion">Fashion</option>
                                <option value="models">Models</option>
                                <option value="tattoos">Tattoos</option>
                            </select>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-default">Post</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>