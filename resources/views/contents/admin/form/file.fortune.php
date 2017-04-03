<% extends("layouts/admin") %>

<% part("content") %>

<h1>{{ $title }}</h1>

<form method="post" action="{{ $route }}">
    {{! httpMethodInput($method) !}}
    {{! csrfInput() !}}

    <!-- File input -->
    <div class="form-group">
        <label for="file" class="control-label">File</label>
        <input type="file" id="file" name="file" class="form-control" value="{{ $entity->getFile() }}">
        <p class="help-block">Example block-level help text here.</p>
    </div>

    <!-- Description input -->
    <div class="form-group">
        <label for="description" class="control-label">Description</label>
        <textarea type="text" id="description" name="description" class="form-control"
                  rows="15">{{ $entity->getDescription() }}</textarea>
    </div>

    <!-- Category select -->
    <div class="form-group">
        <label for="category" class="control-label">Category</label>
        <select type="text" id="category" name="category" class="form-control">
            <% if ($entity->getCategory()->getId() === 0) %>
            <option disabled="disabled" selected="selected">Choose a category</option>
            <% endif %>
            <% foreach ($categories as $category) %>
            <option <% if ($category->getId() === $entity->getCategory()->getId()) %> selected="selected"<% endif %>
                    value="{{ $category->getId() }}">{{ $category->getName() }}</option>
            <% endforeach %>
        </select>
    </div>

    <!-- Controls -->
    <% include("./partials/admin/form/save", compact("showUrl")) %>
</form>

<% endpart %>
