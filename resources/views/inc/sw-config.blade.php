@auth
    <script src="{{ asset('js/enable-push.js') }}"></script>
@endauth
<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function () {
            navigator.serviceWorker.register(window.Laravel.appUrl + '/service-worker.js')
                .then(reg => {

                    console.log('Service worker registered! 😎');
                    console.log('------------------------');
                    console.log(reg);
                    console.log('------------------------');
                    if (typeof initPush !== 'undefined') {
                        initPush();
                    }

                    if (typeof navigator.serviceWorker !== 'undefined') {
                        // if(reg.hasOwnProperty('pushManager')) {

                        // } else {
                        //     console.log('pushManager is not supported');
                        //     return false;
                        // }
                        reg.pushManager.getSubscription()
                            .then(function (subscription) {
                                // Enable any UI which subscribes / unsubscribes from
                                // push messages.
                                // var pushButton = document.querySelector('.js-push-button');
                                // pushButton.disabled = false;
                                if (!subscription) {
                                    // We aren’t subscribed to push, so set UI
                                    // to allow the user to enable push
                                    $("input[name='reminder-btn']").prop('checked', false);
                                    return;
                                }

                                storePushSubscription(subscription);
                                $("input[name='reminder-btn']").prop('checked', true);
                            })
                            .catch(function (err) {
                                console.log('Error during getSubscription() ' + err);
                            });
                    } else {
                        console('service worker not supported');
                    }
                })
                .catch(err => {
                    console.log('😥 Service worker registration failed: ', err);
                });
        });
    } else {
        console.log('Service Worker not supported');
    }
    // reg.onupdatefound = () => {
    //     const installingWorker = reg.installing;
    //     installingWorker.onstatechange = () => {
    //         switch (installingWorker.state) {
    //             case 'installed':
    //                 if (navigator.serviceWorker.controller) {
    //                     // new update available
    //                     resolve(true);
    //                 } else {
    //                     // no update available
    //                     resolve(false);
    //                 }
    //             break;
    //         }
    //     };
    // };
</script>
