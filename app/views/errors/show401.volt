{% extends "templates/base.volt" %}
{% block content %}
<div class="row">
    <div class="col-md-4"></div>
    <div class="col-md-4">
        {{ content() }}
        <div class="jumbotron">
            <h1>Unauthorized</h1>
            <p>You don't have access to this option. Contact an administrator</p>
            <p>{{ link_to('index', 'Home', 'class': 'btn btn-primary') }}</p>
        </div>
    </div>
    <div class="col-md-4"></div>
</div>
{% endblock %}