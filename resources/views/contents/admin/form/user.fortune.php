<% extends("layouts/admin") %>

<% part("content") %>

<h1>{{ $title }}</h1>

<% include("./partials/admin/general/messages", compact("errorMessages", "successMessages")) %>

<form method="post" action="{{ $route }}">
    {{! httpMethodInput($method) !}}
    {{! csrfInput() !}}

    <!-- Name input -->
    <div class="form-group">
        <label for="username" class="control-label">{{ tr("application:userUsername") }}</label>
        <input type="text" id="username" name="username" class="form-control" value="{{ $entity->getUsername() }}">
    </div>

    <!-- E-mail input -->
    <div class="form-group">
        <label for="email" class="control-label">{{ tr("application:userEmail") }}</label>
        <input type="email" id="email" name="email" class="form-control" value="{{ $entity->getEmail() }}">
    </div>

    <!-- Password input -->
    <div class="form-group">
        <label for="password" class="control-label">{{ tr("application:userPassword") }}</label>
        <input type="password" id="password" name="password" class="form-control" autocomplete="off" value="">
    </div>

    <div class="form-group">
        <label for="password_confirmed" class="control-label">{{ tr("application:userConfirmPassword") }}</label>
        <input type="password" id="password_confirmed" name="password_confirmed" class="form-control" value=""
               autocomplete="off">
    </div>

    <!-- Controls -->
    <% include("./partials/admin/form/save", compact("showUrl")) %>
</form>

<% endpart %>