<% extends("layouts/admin") %>

<% part("content") %>

<h1>{{ $title }}</h1>

<form method="post" action="{{ $route }}">
    {{! httpMethodInput($method) !}}
    {{! csrfInput() !}}

    <!-- Name input -->
    <div class="form-group">
        <label for="name" class="control-label">
            {{ tr("application:categoryName") }}
        </label>
        <input type="text" id="name" name="name" class="form-control" value="{{ $entity->getName() }}">
    </div>

    <!-- Controls -->
    <% include("./partials/admin/form/save", compact("showUrl")) %>
</form>

<% endpart %>
