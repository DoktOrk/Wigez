<% extends("layouts/admin") %>

<% part("content") %>

<h1>{{ $title }}</h1>

{{! $grid !}}

<% if ($createUrl) %>
<div class="form-group pmd-textfield pmd-textfield-floating-label">
    <a class="btn btn-success pmd-checkbox-ripple-effect" href="{{ $createUrl }}">Create New</a>
</div>
<% endif %>

<% endpart %>