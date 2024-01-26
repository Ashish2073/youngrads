$(document).ready(function () {
    $("body").on("click", ".common-edit-profile", function (e) {
        let url = $(this).data("url");
        $(".dynamic-title").html("Edit Profile");
        
        getContent({
            beforeSend: modalLoader,
            url: url,
            success: function (data) {
                $(".dynamic-body").html(data);
                commonScript();
            },
        });
        
    });

    
});

function commonScript() {

    validateForm($("#update-profile-form"), {
        rules: {
            first_name: {
                required: true,
            },
            last_name: {
                required: true,
            },
            password: {
                minlength: 6
            },
            "password_confirmation": {
                equalTo : "#password"
            },
            email: {
                email: true
            }
        },
        messages: {

        }
    });


    submitForm($("#update-profile-form"), {
        beforeSubmit: function () {
            submitLoader("#submit-btn");
        },
        success: function (data) {
            setAlert(data);
            if (data.success) {
                submitReset("#submit-btn", "Update Profile");
                showMessage(
                    "<strong>Success!</strong> Profile updated successfully!",
                    data.code
                );
                modalReset();
            } else {
                submitReset("#submit-btn", "Update Profile");
                $(".dynamic-body").html(data);
            }
            commonScript();
        },
        complete: function () {
            submitReset("#submit-btn", "Update Profile");
            commonScript();
        },
    });
}
