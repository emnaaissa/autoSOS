$(document).ready(function () {
    loadUsers();

    // Recherche dynamique
    $("#searchUser").on("keyup", function () {
        var value = $(this).val().toLowerCase();
        $("#userTableBody tr").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});

function loadUsers() {
    $.ajax({
        url: 'auth/get_users.php',
        type: 'GET',
        dataType: 'json',
        success: function (users) {
            let html = '';
            users.forEach(user => {
                let roleBadge = user.role === 'mecanicien'
                    ? '<span class="bg-orange-100 text-orange-600 px-2 py-1 rounded text-xs font-bold">MÉCANICIEN</span>'
                    : '<span class="bg-blue-100 text-blue-600 px-2 py-1 rounded text-xs font-bold">CLIENT</span>';

                html += `
                <tr class="hover:bg-gray-50 transition" id="user-${user.id}">
                    <td class="px-6 py-4 font-mono text-gray-400">#${user.id}</td>
                    <td class="px-6 py-4 font-bold text-slate-700">${user.nom} ${user.prenom}</td>
                    <td class="px-6 py-4 text-gray-600">${user.email}</td>
                    <td class="px-6 py-4">${roleBadge}</td>
                    <td class="px-6 py-4 text-center space-x-2">
                        <button onclick="deleteUser(${user.id})" class="text-red-500 hover:text-red-700 p-2">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>`;
            });
            $('#userTableBody').html(html);
        }
    });
}

function deleteUser(id) {
    if (confirm("Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.")) {
        $.ajax({
            url: 'auth/delete_user.php',
            type: 'POST',
            data: { id: id },
            success: function (response) {
                $(`#user-${id}`).fadeOut();
            }
        });
    }
}