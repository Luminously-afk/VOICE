/* =========================================
   V.O.I.C.E. System - Main Script
   ========================================= */

document.addEventListener("DOMContentLoaded", function() {
    
    // 1. Auto-hide Flash Messages (Alerts) after 3 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            let bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 3000);
    });

    // Pwede ka magdagdag ng iba pang general frontend logic dito
});

/**
 * 2. Reddit-Style Voting Logic (AJAX)
 * Ito ang nag-a-update ng score kapag pinindot ang upvote/downvote
 */
function vote(postId, type, urlRoot) {
    // Gumagamit tayo ng fetch API para kausapin ang PostController
    fetch(urlRoot + '/post/vote', {
        method: 'POST',
        headers: { 
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: `post_id=${postId}&type=${type}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Hanapin ang score element sa HTML
            let scoreElement = document.getElementById('score-' + postId);
            let currentScore = parseInt(scoreElement.innerText);
            
            // Compute new score
            let newScore = (type === 'up') ? currentScore + 1 : currentScore - 1;
            scoreElement.innerText = newScore;
            
            // Palitan ang kulay base sa score (Green pag positive, Red pag negative)
            if (newScore > 0) { 
                scoreElement.className = 'fw-bold my-1 text-success'; 
            } else if (newScore < 0) { 
                scoreElement.className = 'fw-bold my-1 text-danger'; 
            } else { 
                scoreElement.className = 'fw-bold my-1 text-dark'; 
            }
        } else {
            alert(data.message || 'Error processing your vote. Please try logging in again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}