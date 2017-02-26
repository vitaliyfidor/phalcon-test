{% extends "templates/base.volt" %}
{% block content %}
<div class="row">
    <div class="col-md-4"></div>
    <div class="col-md-4">
        {{ content() }}
        <div class="jumbotron">
            <h1>Page not found</h1>
            <p>Sorry, you have accessed a page that does not exist or was moved</p>
            <p>{{ link_to('/', 'Home', 'class': 'btn btn-primary') }}</p>
        </div>
    </div>
    <div class="col-md-4"></div>
</div>
{% endblock %}