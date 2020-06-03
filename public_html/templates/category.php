<!-- Modal--> 
<div class="modal fade" id="form_category" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add New Category</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        
        <form id="category_form" onsubmit="return false">
          <div class="form-group">
            <label>Category Name</label> <!--All textual <input>, <textarea>, and <select> elements with .form-control are set to width: 100%; by default.-->
            <input type="text" class="form-control" name="category_name" id="category_name" aria-describedby="emailHelp" placeholder="Enter category name">
            <small id="cat_error" class="form-text text-muted"></small>
          </div>

          <div class="form-group">
            <label>Parent Category</label>
            <select class="form-control" id="parent_cat" name="parent_cat">
            </select>
          </div>

          <button type="submit" class="btn btn-primary">Submit</button>
        </form>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>


  <!--ARIA (Accessible Rich Internet Applications) defines a way to make Web content and Web applications more accessible to people with disabilities.The hidden attribute is new in HTML5 and tells browsers not to display the element. The aria-hidden property tells screen-readers if they should ignore the element.aria-hidden="true" tells screen readers to ignore that element as well as all of its children.-->
  

<!-- aria-labelledby: Identifies the element (or elements) that labels the current element.
aria-hidden (state): Indicates that the element and all of its descendants are not visible or perceivable to any user as implemented by the author.-->