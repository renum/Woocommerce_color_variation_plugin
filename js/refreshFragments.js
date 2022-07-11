    
    refresh_fragments();
    setInterval(refresh_fragments,6000);
    function refresh_fragments(){
        console.log('refreshing fragments');
        jQuery( document.body ).trigger( 'wc_fragment_refresh' );

    }
