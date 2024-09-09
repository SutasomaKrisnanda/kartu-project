<x-home.layout>
    <x-slot:nickname>{{ $user->nickname }}</x-slot>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .profile-container {
            max-width: 400px;
            margin: 50px auto;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .profile-header {
            text-align: center;
        }

        .profile-pic img {
            border-radius: 50%;
            width: 150px;
            height: 150px;
            object-fit: cover;
        }

        .profile-pic button {
            margin-top: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            padding: 8px 16px;
            cursor: pointer;
            border-radius: 5px;
        }

        .profile-details {
            margin-top: 20px;
        }

        .editable-field {
            margin-bottom: 15px;
        }

        .editable-field span {
            font-size: 1.2em;
        }

        .editable-field button {
            margin-left: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
        }

        .editable-field input {
            font-size: 1.2em;
            padding: 5px;
            width: 70%;
        }

        .save-cancel {
            text-align: center;
            margin-top: 20px;
        }

        .save-cancel button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            margin: 0 5px;
        }

        .save-cancel button:nth-child(2) {
            background-color: #dc3545;
        }
    </style>
    <div class="profile-container">
        <div class="profile-header">
            <!-- Profile Picture -->
            <div class="profile-pic">
                <img src="images/kaveh-picture.jpg" alt="Profile Picture" id="profilePic">
                <input type="file" id="fileInput" style="display:none;" accept="image/*" onchange="changeProfilePic()">
                <button onclick="document.getElementById('fileInput').click()">Edit Photo</button>
            </div>

            <!-- Nickname and Username -->
            <div class="profile-details">
                <div class="editable-field">
                    <span id="nicknameDisplay">John Doe</span>
                    <button onclick="editField('nickname')">Edit</button>
                    <input type="text" id="nicknameInput" style="display:none;">
                </div>

                <div class="editable-field">
                    <span id="usernameDisplay">@johndoe</span>
                    <button onclick="editField('username')">Edit</button>
                    <input type="text" id="usernameInput" style="display:none;">
                </div>
            </div>
        </div>

        <!-- Save and Cancel Buttons -->
        <div class="save-cancel" id="saveCancelButtons" style="display:none;">
            <button onclick="saveChanges()">Save</button>
            <button onclick="cancelChanges()">Cancel</button>
        </div>
    </div>

    <script>
        // Handle profile picture change
        function changeProfilePic() {
            const fileInput = document.getElementById('fileInput');
            const profilePic = document.getElementById('profilePic');
            const file = fileInput.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    profilePic.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }

        // Edit fields (Nickname and Username)
        let originalNickname = '';
        let originalUsername = '';

        function editField(field) {
            if (field === 'nickname') {
                const nicknameDisplay = document.getElementById('nicknameDisplay');
                const nicknameInput = document.getElementById('nicknameInput');

                originalNickname = nicknameDisplay.innerText;
                nicknameDisplay.style.display = 'none';
                nicknameInput.style.display = 'inline';
                nicknameInput.value = originalNickname;
            } else if (field === 'username') {
                const usernameDisplay = document.getElementById('usernameDisplay');
                const usernameInput = document.getElementById('usernameInput');

                originalUsername = usernameDisplay.innerText;
                usernameDisplay.style.display = 'none';
                usernameInput.style.display = 'inline';
                usernameInput.value = originalUsername;
            }

            document.getElementById('saveCancelButtons').style.display = 'block';
        }

        // Save Changes
        function saveChanges() {
            const nicknameInput = document.getElementById('nicknameInput');
            const usernameInput = document.getElementById('usernameInput');

            if (nicknameInput.style.display === 'inline') {
                document.getElementById('nicknameDisplay').innerText = nicknameInput.value;
                nicknameInput.style.display = 'none';
                document.getElementById('nicknameDisplay').style.display = 'inline';
            }

            if (usernameInput.style.display === 'inline') {
                document.getElementById('usernameDisplay').innerText = usernameInput.value;
                usernameInput.style.display = 'none';
                document.getElementById('usernameDisplay').style.display = 'inline';
            }

            document.getElementById('saveCancelButtons').style.display = 'none';
        }

        // Cancel Changes
        function cancelChanges() {
            document.getElementById('nicknameDisplay').innerText = originalNickname;
            document.getElementById('usernameDisplay').innerText = originalUsername;

            document.getElementById('nicknameInput').style.display = 'none';
            document.getElementById('usernameInput').style.display = 'none';

            document.getElementById('nicknameDisplay').style.display = 'inline';
            document.getElementById('usernameDisplay').style.display = 'inline';

            document.getElementById('saveCancelButtons').style.display = 'none';
        }
    </script>
</x-home.layout>
