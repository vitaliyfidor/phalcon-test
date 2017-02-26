{% extends "templates/base.volt" %}
{% block content %}
<div class="row">
    <div class="col-md-12">
        {{ content() }}
        {% for user in page.items %}
            {% if loop.first %}
                <table class="table table-bordered table-striped" id="mainTable">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Group</th>
                        </tr>
                    </thead>
                    <tbody>
            {% endif %}
                <tr>
                    <td>{{ user.id }}</td>
                    <td>{{ user.username }}</td>
                    <td>{{ user.email }}</td>
                    <td>{{ user.role }}</td>
                    <td>{{ user.getUserGroups().name }}</td>
                    <td width="7%">{{ link_to("users/edit/" ~ product.id, '<i class="glyphicon glyphicon-edit"></i> Edit', "class": "btn btn-default") }}</td>
                    <td width="7%">{{ link_to("users/delete/" ~ product.id, '<i class="glyphicon glyphicon-remove"></i> Delete', "class": "btn btn-default") }}</td>
                </tr>
            {% if loop.last %}
                </tbody>
                <tbody>
                    <tr>
                        <td colspan="5" align="right">
                             <div class="btn-group">
                                {{ link_to("users/search", '<i class="icon-fast-backward"></i> First', "class": "btn") }}
                                {{ link_to("users/search?page=" ~ page.before, '<i class="icon-step-backward"></i> Previous', "class": "btn") }}
                                {{ link_to("users/search?page=" ~ page.next, '<i class="icon-step-forward"></i> Next', "class": "btn") }}
                                {{ link_to("users/search?page=" ~ page.last, '<i class="icon-fast-forward"></i> Last', "class": "btn") }}
                                <span class="help-inline">{{ page.current }} of {{ page.total_pages }}</span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            {% endif %}
        {% else %}
        <table class="table table-bordered table-striped" id="mainTable">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Group</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="5" align="center">No users are recorded</td>
                </tr>
            </tbody>
        </table>
        {% endfor %}
    </div>
</div>
<div class="row">
    <div class="col-md-12">
    {{ link_to("signup/register", "Create new User", "class": "btn btn-primary btn-lg", "role": "button") }}
    </div>
</div>
{% endblock %}