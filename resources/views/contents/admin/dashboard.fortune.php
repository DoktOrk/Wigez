<% extends("layouts/admin") %>

<h1>{{ $title }}</h1>

<% include("./partials/admin/general/messages", compact("errorMessages", "successMessages")) %>

<% part("content") %>

<% endpart %>
