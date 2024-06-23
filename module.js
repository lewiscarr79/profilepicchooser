M.block_profilepicchooser = {
    // Initialize the profile picture chooser functionality
    init: function(Y, changeUrl) {
        // Get the button element that triggers the profile picture chooser
        var button = document.getElementById('profilepicchooser-btn');
        
        // Add click event listener to the button
        button.addEventListener('click', function() {
            // Load required Moodle modules
            require(['core/modal_factory', 'core/modal_events', 'core/ajax', 'core/str'], function(ModalFactory, ModalEvents, Ajax, Str) {
                // Get the localized string for the modal title
                Str.get_string('choosepicture', 'block_profilepicchooser').then(function(title) {
                    // Create the modal with the localized title
                    ModalFactory.create({
                        type: ModalFactory.types.DEFAULT,
                        title: title,
                        // Create a container for profile picture choices with accessibility attributes
                        body: '<div id="profilepic-choices" role="group" aria-label="' + M.util.get_string('profilepicchoices', 'block_profilepicchooser') + '"></div>',
                    }).then(function(modal) {
                        // Display the modal
                        modal.show();
                        // Fetch available profile pictures
                        fetch(changeUrl)
                            .then(response => response.json())
                            .then(images => {
                                var choices = document.getElementById('profilepic-choices');
                                // Create a button for each profile picture
                                images.forEach(function(imageUrl, index) {
                                    var button = document.createElement('button');
                                    button.className = 'profilepic-choice m-2';
                                    // Add accessibility label
                                    button.setAttribute('aria-label', M.util.get_string('selectprofilepic', 'block_profilepicchooser', index + 1));
                                    
                                    // Create image element
                                    var img = document.createElement('img');
                                    img.src = imageUrl;
                                    img.alt = M.util.get_string('profilepicture', 'block_profilepicchooser', index + 1);
                                    img.style = 'width: 100px; height: 100px;';
                                    
                                    button.appendChild(img);
                                    
                                    // Add click event listener to each profile picture button
                                    button.addEventListener('click', function() {
                                        // Send request to update profile picture
                                        fetch(M.cfg.wwwroot + '/blocks/profilepicchooser/update_picture.php', {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/x-www-form-urlencoded',
                                            },
                                            body: 'imageurl=' + encodeURIComponent(imageUrl),
                                            credentials: 'same-origin'
                                        }).then(response => response.text())
                                        .then(data => {
                                            try {
                                                let jsonData = JSON.parse(data);
                                                if (jsonData.success) {
                                                    // Display success message
                                                    Str.get_string('changessaved', 'block_profilepicchooser').then(function(changessaved) {
                                                        modal.setBody(changessaved);
                                                        // Reload page after a short delay
                                                        setTimeout(function() {
                                                            window.location.reload(true);
                                                        }, 1500);
                                                    });
                                                } else {
                                                    // Display generic error message
                                                    modal.setBody('Error: Unknown error occurred');
                                                }
                                            } catch (e) {
                                                // Display error message if response is not valid JSON
                                                modal.setBody('Error: ' + data);
                                            }
                                        }).catch(error => {
                                            // Log and display any errors
                                            console.error('Error:', error);
                                            modal.setBody('Error: ' + error.message);
                                        });
                                    });
                                    
                                    // Add the profile picture button to the choices container
                                    choices.appendChild(button);
                                });
                            });
                    });
                });
            });
        });
    }
};