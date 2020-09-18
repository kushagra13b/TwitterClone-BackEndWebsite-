$(function()
{
    $(document).on( "click", ".follow-btn", function() 
	{
    	let user_id = $(this).data('user_id');
    	let profileID = $(this).data('follow');
    	let followID = $(this).data('profile');
    
        let postData = {
            user_id: user_id,
        	profileID: profileID,
        	followID: followID
        }
        
        console.log(postData);
        
    	$.post
    	(
        	'http://localhost/twitter/core/ajax/follow.php',
        	postData,
            function(data)
        	{
        	    $('.edit-button span').html(data);
        	}
    	);
	});
});