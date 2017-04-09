<% extends("layouts/admin") %>

<% part("content") %>

<h1>{{ $title }}</h1>

<form method="post" action="{{ $route }}">
    {{! httpMethodInput($method) !}}
    {{! csrfInput() !}}

    <!-- Title input -->
    <div class="form-group">
        <label for="title" class="control-label">{{ tr("application:pageTitle") }}</label>
        <input type="text" id="title" name="title" class="form-control" value="{{ $entity->getTitle() }}">
    </div>

    <!-- Body input -->
    <div class="form-group">
        <label for="body" class="control-label">{{ tr("application:pageBody") }}</label>
        <textarea id="body" class="form-control wysiwyg" name="body" rows="15">{{ $entity->getBody() }}</textarea>
    </div>

    <!-- Controls -->
    <% include("./partials/admin/form/save", compact("showUrl")) %>
    <% include("./partials/admin/form/editor") %>
</form>

<% endpart %>
