<?php

include_once __DIR__ . '/db.php';

session_start();


function hasVoted(){
    include_once __DIR__ . '/db.php';


    $student_id = $_SESSION['id'];

    $sql = "SELECT COUNT(*) AS vote_count FROM votes WHERE student_id = :student_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $hasVoted = $result['vote_count'] == 0;

    return $hasVoted;

}



// Redirect to login page if not logged in
if (!isset($_SESSION['student_id'])) {
    header('Location: login.php'); 
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5">
    <meta name="author" content="AdminKit">
    <meta name="keywords" content="e-vote, voting system, secure voting, elections">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="shortcut icon" href="img/icons/professional-icon.png" />
    <title>E-Vote System</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="css/light.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <script>
    </script>
</head>

<body data-theme="default" data-layout="fluid" data-sidebar-position="left" data-sidebar-layout="default">
    <div class="wrapper">
        <div class="main">
            <?php include_once 'includes/navbar.php'; ?>

            <main class="content">  
                <section class="hero-section text-center mb-4" style="background-image: url('img/photos/ok.png'); background-size: cover; background-repeat: no-repeat; background-position: center;">
                    <div class="hero-content">
                        <div class="hero-text">
                            <h1 class="hero-title">Welcome to the E-Vote Platforms!</h1>
                            <p class="hero-description"> Use this platform to apply for candidacy in just a few clicks.</p>
                        </div>
                        <div class="hero-model">
                            <img src="img/icons/click-ezgif.com-gif-maker.gif" alt="3D Model Voting" class="model-animation">
                        </div>
                    </div>
                </section>


                
            </main>

            <footer class="footer">
                <div class="container-fluid">
                    <div class="text-muted">
                        <div class="text-center">
                            <a class="text-muted"><strong>ISPSC-Tagudin Campus</strong></a> &copy;
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="js/app.js"></script>

     <!--
    <script>

$(document).ready(function() {

    let student_id = "<?php echo $_SESSION['student_id']; ?>";


    $.ajax({
        type: 'POST',
        url: '../admin/process/view-votes-by-id.php',
        data: {
            action: 1,
            student_id: student_id
        },
        success: function(res) {
            var res = JSON.parse(res);

            if (res.success) {
                // Clear the previous table content
                $('#vote-section .table tbody').empty();

                // Loop through each voting record and add to the table
                res.data.forEach(function(vote) {
                    let row = `
                        <tr>
                            <td>${vote.position_name}</td>
                            <td>${vote.candidate_name}</td>
                            <td>${vote.partylist_name}</td>
                            
                        </tr>
                    `;
                    $('#vote-section .table tbody').append(row);
                });

            }
        }
    });
});

    const selectedCandidates = {};

    function selectCard(positionName, candidateId) {
        const normalizedPositionName = positionName.replace(/\s+/g, '_');

        if (selectedCandidates[normalizedPositionName]) {
            $(`#${normalizedPositionName}-candidate${selectedCandidates[normalizedPositionName]}`).removeClass('selected');
        }

        $(`#${normalizedPositionName}-candidate${candidateId}`).addClass('selected');
        selectedCandidates[normalizedPositionName] = candidateId;
    }

    // Fetch voting data when the page loads
    $(document).ready(function() {
        fetchVotingData();
    });

    function fetchVotingData() {
        $.ajax({
            url: 'fetch_voting_data.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                response.positions.forEach(position => {
                    const normalizedPositionName = position.position_name.replace(/\s+/g, '_');

                    const positionContainer = `
                        <div class="row mb-2 mb-xl-3">
                            <div class="col-auto d-none d-sm-block">
                                <h4><i class="fas fa-user-tie"></i> <strong>Vote for</strong> ${position.position_name}</h4>
                            </div>
                        </div>
                        <div class="row" id="position-${normalizedPositionName}"></div>`;
                    $('#positions-container').append(positionContainer);

                    position.candidates.forEach(candidate => {
                        console.log(`Candidate Data:`, candidate); // Log candidate data

                        const candidateCard = `
                            <div class="col-md-4">
                                <div class="card vote-card" 
                                     data-position-id="${position.position_id}" 
                                      data-partylist-id="${candidate.partylist_id || 'None'}" 
                                     id="${normalizedPositionName}-candidate${candidate.candidate_id}">
                                    <img src="${candidate.image_path}" alt="${candidate.name || 'Candidate Image'}" 
                                    onerror="this.src='https://via.placeholder.com/90';">

                                    <div class="card-body">
                                        <h5 class="card-title">${candidate.candidate_name}</h5>
                                        <p class="card-department">${candidate.department_name}</p>
                                        <p class="card-partylist">${candidate.partylist_name}</p>
                                        <span class="badge-selected">Selected</span>
                                    </div>
                                </div>
                            </div>`;
                        $(`#position-${normalizedPositionName}`).append(candidateCard);

                        // Attach click event to select candidate
                        $(`#${normalizedPositionName}-candidate${candidate.candidate_id}`).on('click', function() {
                            selectCard(position.position_name, candidate.candidate_id);
                        });
                    });
                });
            },
            error: function(err) {
                console.error('Error fetching voting data:', err);
                alert('Failed to load voting data. Please try again later.');
            }
        });
    }

    // Show confirmation modal
    $('#submitVote').on('click', function() {
        if (Object.keys(selectedCandidates).length === 0) {
            alert('Please select candidates before submitting your vote.');
            return;
        }
        populateConfirmationModal();
        $('#confirmationModal').modal('show');
    });

    // Populate modal with selected candidates
    function populateConfirmationModal() {
        const selectedCandidatesContainer = $('#selectedCandidatesContainer');
        selectedCandidatesContainer.empty();

        Object.keys(selectedCandidates).forEach(positionName => {
            const candidateId = selectedCandidates[positionName];
            const candidateCard = $(`#${positionName}-candidate${candidateId}`);
            const candidateName = candidateCard.find('.card-title').text();
            const department = candidateCard.find('.card-department').text();
            const partyList = candidateCard.find('.card-partylist').text();
            const candidateImage = candidateCard.find('img').attr('src');

            const modalEntry = `
                <div class="row mb-3">
                    <div class="col-auto">
                        <img src="${candidateImage}" class="img-thumbnail" width="60" alt="https://via.placeholder.com/90">
                    </div>
                    <div class="col">
                        <h5 class="mb-0">${candidateName}</h5>
                        <p class="mb-0 text-muted">${department}</p>
                        <p class="mb-0 text-muted">${partyList}</p>
                        <small class="text-muted">Position: ${positionName.replace('_', ' ')}</small>
                    </div>
                </div>`;
            
            selectedCandidatesContainer.append(modalEntry);
        });
    }

    // Client-side confirmation of vote submission
    $('#confirmVote').on('click', function() {
        const votes = Object.keys(selectedCandidates).map(positionName => {
            const candidateId = selectedCandidates[positionName];
            const candidateCard = $(`#${positionName}-candidate${candidateId}`);

            // Check if the candidateCard exists
            if (!candidateCard.length) {
                console.error(`Candidate card for ${positionName} and candidate ID ${candidateId} does not exist.`);
                return null; // Skip if the card does not exist
            }

            const partylistId = candidateCard.data('partylist-id'); // Retrieve partylist ID
            const positionId = candidateCard.data('position-id'); // Get the correct position_id

            // Log values to help debug undefined partylist_id
            console.log(`Position: ${positionName}, Candidate ID: ${candidateId}, Partylist ID: ${partylistId}, Position ID: ${positionId}`);

            // Validate the partylistId and positionId
            if (!partylistId || !positionId) {
                alert("Error: Partylist ID or Position ID is missing.");
                return null; // Stop execution if IDs are invalid
            }

            return {
                position_id: positionId,
                candidate_id: candidateId,
                partylist_id: partylistId
            };
        }).filter(vote => vote !== null); // Filter out any null values

        if (votes.length === 0) {
            alert("Error: No valid votes found.");
            return; // Stop submission if no valid votes
        }

        $.ajax({
            url: 'submit_vote.php',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                student_id: '<?php echo $_SESSION['student_id']; ?>',
                votes: votes
            }),
            success: function(response) {
                const result = JSON.parse(response);
                alert(result.message);
                if (result.status === 'success') {
                    window.location.href = 'thanks.php';
                    //window.location.reload(); // Reload page after successful vote
                }
            },
            error: function(err) {
                console.error('Error submitting vote:', err);
                alert('An error occurred while submitting your vote. Please try again.');
            }
        });
    });
</script>
-->
</body>
</html>
