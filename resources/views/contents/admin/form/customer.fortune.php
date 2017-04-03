<% extends("layouts/admin") %>

<% part("content") %>

<h1>{{ $title }}</h1>

<form method="post" action="{{ $route }}">
    {{! httpMethodInput($method) !}}
    {{! csrfInput() !}}

    <!-- Name input -->
    <div class="form-group">
        <label for="name" class="control-label">Name</label>
        <input type="text" id="name" name="name" class="form-control" value="{{ $entity->getName() }}">
    </div>

    <!-- E-mail input -->
    <div class="form-group">
        <label for="email" class="control-label">E-mail</label>
        <input type="email" id="email" name="email" class="form-control" value="{{ $entity->getEmail() }}">
    </div>

    <!-- Password input -->
    <div class="form-group">
        <label for="password" class="control-label">Password</label>
        <input type="password" id="password" name="password" class="form-control" autocomplete="off" value="">
    </div>

    <div class="form-group">
        <label for="password_confirmed" class="control-label">Confirm Password</label>
        <input type="password" id="password_confirmed" name="password_confirmed" class="form-control" value=""
               autocomplete="off">
    </div>

    <!-- Category select -->
    <div class="form-group">
        <h3 class="control-label">Categories</h3>
        <div class="pmd-card-body">
            <% foreach ($allCategories as $category) %>
            <div class="checkbox pmd-default-theme">
                <input type="checkbox" class="pmd-checkbox" name="categories[]" id="category-{{ $category->getId() }}"
                <% if (in_array($category->getId(), $currentCategories, true)) %> checked="checked"<% endif %>
                value="{{ $category->getId() }}">
                <label class="control-label" for="category-{{ $category->getId() }}">{{ $category->getName() }}</label>
            </div>
            <% endforeach %>
        </div>
    </div>

    <!-- Controls -->
    <% include("./partials/admin/form/save", compact("showUrl")) %>
</form>

<% endpart %>