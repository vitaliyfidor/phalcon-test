{% extends "templates/base.volt" %}
{% block content %}
<div class="row">
    <div class="col-md-4"></div>
    <div class="col-md-4">
        {{ content() }}
        <div class="jumbotron">
            <h1>Internal Error</h1>
            <p>Something went wrong, if the error continue please contact us</p>
            <p>{{ link_to('index', 'Home', 'class': 'btn btn-primary') }}</p>
        </div>
    </div>
    <div class="col-md-4"></div>
</div>
    {% endblock %}