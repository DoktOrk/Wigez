<% extends("layouts/admin") %>

<% part("content") %>

<h1>{{ $title }}</h1>

<% include("./partials/admin/general/messages", compact("errorMessages", "successMessages")) %>

<% endpart %>
