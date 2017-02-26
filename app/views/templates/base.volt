<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        {{ get_title() }}
        {{ stylesheet_link('css/bootstrap.min.css') }}
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="desc">
    </head>
    <body>
        <div id="content">
            <div class="container text-center">
                <div class="row">
                    <div class="col-md-8">
                        <h1 class="page-header">Phalcon Test CRUD with ACL</h1>
                    </div>
                    <div class="col-md-4">
                        {% if session.get('auth') == null %}
                            {{ link_to("signup/login", "Login", "class": "btn btn-primary btn-lg", "role": "button") }}
                        {% else %}
                            {{ link_to("signup/logout", "Logout", "class": "btn btn-primary btn-lg", "role": "button") }}
                        {% endif %}
                    </div>
                </div>
                <div class="row">

                </div>
                {% block content %}{% endblock %}
            </div>
        </div>
        {{ javascript_include('js/jquery-3.1.1.min.js') }}
        {{ javascript_include('js/bootstrap.min.js') }}
        {{ javascript_include('js/main.js') }}
    </body>
</html>