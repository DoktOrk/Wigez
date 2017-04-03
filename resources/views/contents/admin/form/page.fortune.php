<% extends("layouts/admin") %>

<% part("content") %>

<h1>{{ $title }}</h1>

<form method="post" action="{{ $route }}">
    {{! httpMethodInput($method) !}}
    {{! csrfInput() !}}

    <!-- Title input -->
    <div class="form-group pmd-textfield pmd-textfield-floating-label">
        <label for="title" class="control-label">Title</label>
        <input type="text" id="title" name="title" class="form-control" value="{{ $entity->getTitle() }}">
    </div>

    <!-- Body input -->
    <div class="form-group pmd-textfield pmd-textfield-floating-label">
        <label for="body" class="control-label">Body</label>
        <textarea id="body" class="form-control" name="body" rows="15">{{ $entity->getBody() }}</textarea>
    </div>

    <!-- Controls -->
    <% include("./partials/admin/form/save", compact("showUrl")) %>
</form>

<% endpart %>