<?php
include 'db_connect.php';
$qry = $conn->query("SELECT r.*,concat(s.firstname,' ',s.middlename,' ',s.lastname) as name,s.student_code,concat(c.level,'-',c.section) as class,s.gender FROM results r inner join classes c on c.id = r.class_id inner join students s on s.id = r.student_id where r.id = ".$_GET['id'])->fetch_array();
foreach($qry as $k => $v){
	$$k = $v;
}
?>

<style>
    /* Base responsive styles */
    * {
        box-sizing: border-box;
    }
    
    body {
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
    }
    
/* ========== SCREEN‑ONLY LAYOUT STYLES ========== */
/* ========== SCREEN‑ONLY LAYOUT STYLES ========== */
@media screen {
    .responsive-container {
        width: 100%;
        /* width: -0px; */
        max-width: 2000px;   /* increased from 1500px */
        height: 1000px;
        margin: 0 auto;
        padding: 15px;
        left: 28px;
        top: 70%;
    }
    
    /* all other screen styles remain exactly the same */
    .certificate-border-outer {
        border: 4px solid #0047AB;
        padding: clamp(15px, 3vw, 30px);
        width: 1085px;
        left: -50%;
        top: -5px;
        height: 1620px;
        position: relative;
        background: white;
    }

    .certificate-border-middle {
        border: 4px solid #e0bf00;
        padding: clamp(10px, 2vw, 20px);
        width: 1015px;
        height: 1550px;
    }

    .certificate-border-inner {
        border: 4px solid #0047AB;
        padding: clamp(8px, 1.5vw, 15px);
        width: 965px;
        height: 1500px;
        position: relative;
        background: white;
    }
    
    .watermark-img {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        opacity: 0.1;
        width: min(495px, 90%);
        max-width: 495px;
        height: auto;
        z-index: 1;
        pointer-events: none;
    }
}
    /* Header responsive */
    .certificate-header {
        text-align: center;
        position: relative;
        z-index: 2;
        padding: clamp(5px, 1vw, 15px) 0;
    }
    
.certificate-header img {
    height: clamp(80px, 18vw, 150px);
    width: auto;
    display: block;
    margin: 0 auto;
    max-width: 260%;
    border: none !important;        /* Remove border */
    outline: none !important;       /* Remove outline */
    box-shadow: none !important;    /* Remove shadow (agar ho) */
}


    
    .certificate-header h2 {
        font-size: clamp(1.2rem, 2.5vw, 1.8rem);
        margin: clamp(3px, 0.5vw, 8px) 0;
        font-weight: bold;
    }
    
    .certificate-header h2 {
        font-size: clamp(1rem, 2vw, 1.5rem);
        margin: clamp(2px, 0.3vw, 5px) 0;
        font-weight: bold;
    }
    
    /* Student info table */
    .student-info-table {
        width: 150%;
        margin: clamp(15px, 3vw, 30px) 0;
        font-size: clamp(1.1rem, 1.8vw, 1.3rem);
        position: relative;
        z-index: 2;
    }
    
    .student-info-table td {
        padding: clamp(5px, 0.8vw, 10px);
        vertical-align: top;
    }
    
    /* Main results table */
    .results-table {
        width: 100%;
        border-collapse: collapse;
        margin: clamp(10px, 2vw, 20px) 0;
        font-size: clamp(0.7rem, 1.3vw, 0.9rem);
        position: relative;
        z-index: 2;
    }
    
    .results-table th,
    .results-table td {
        padding: clamp(5px, 0.8vw, 10px);
        border: 1px solid #ddd;
        text-align: center;
    }
    
    .results-table th {
        background-color: #f8f9fa;
        font-weight: bold;
    }
    
    /* Signature section responsive */
    .signature-section {
        margin-top: clamp(25px, 4vw, 45px);
        display: flex;
        justify-content: space-between;
        padding: 0 clamp(10px, 3vw, 50px);
        flex-wrap: wrap;
        gap: clamp(15px, 3vw, 30px);
        position: relative;
        z-index: 2;
    }
    
    .signature-box {
        text-align: center;
        flex: 1;
        min-width: 120px;
    }
    
    .signature-box p {
        margin: clamp(3px, 0.5vw, 5px) 0;
        font-size: clamp(0.8rem, 1.5vw, 1rem);
    }
    
    /* Print styles */
/* ============================================
   PRINT STYLES FOR CERTIFICATE DOCUMENT
   These styles only apply when printing
   ============================================ */

@media print {
  
  /* ============================
     PAGE SETTINGS
     ============================ */
  
@page {
  size: 14.3in 11in; /* Width then Height */
  margin: 0; /* Edge-to-edge printing */
}

body {
  margin: 0;
  padding: 0;
  width: 14.3in;
  height: 11in;
  box-sizing: border-box;
}
  
  /* ============================
     WATERMARK STYLING
     ============================ */
  
  .watermark-img {
    display: block !important;
    opacity: 0.1 !important;
    position: fixed !important;
    top: 50% !important;
    left: 50% !important;
    transform: translate(-50%, -50%) !important;
    z-index: 1 !important;
    max-width: 100% !important;
    max-height: 100% !important;
    width: 220mm !important;
    height: 240mm !important;
    
    /* Ensure watermark prints */
    -webkit-print-color-adjust: exact !important;
    
    print-color-adjust: exact !important;
  }
  
  /* ============================
     ELEMENTS TO HIDE WHEN PRINTING
     ============================ */
  
  /* Hide interactive elements that aren't needed on paper */
  .modal-footer,
  button,
  .no-print,
  [data-print-hide] {
    display: none !important;
  }
  
  /* ============================
     CERTIFICATE CONTAINER
     ============================ */
  
  .certificate-wrapper {
    /* Ensure full page utilization */
    max-width: 2000px !important;
    width: 1700px !important;
    height: 1308px !important;
    
    /* Reset any transforms that might affect layout */
    transform: none !important;
    transform-origin: 0 0 !important;
    
    /* Remove any unwanted spacing */
    margin: 0 !important;
    padding: 0 !important;
    
    /* Ensure proper positioning */
    position: relative !important;
    top: 0 !important;
    left: 0 !important;
    
    /* Ensure certificate prints as a single unit */
    page-break-inside: avoid !important;
    break-inside: avoid !important;
    page-break-after: avoid !important;
    page-break-before: avoid !important;
  }
  
  /* ============================
     CERTIFICATE BORDER STYLES
     ============================ */
  
  .certificate-border-outer {
    border: 8px solid #0047AB !important;
    border-bottom: 8px solid #0047AB !important;
    box-sizing: border-box !important;
    width: calc(100% - 0px) !important;
    height: 100% !important;
    position: relative !important;
  }
  
  .certificate-border-middle {
    border: 4px solid #e0bf00 !important;
    border-bottom: 4px solid #e0bf00 !important;
    box-sizing: border-box !important;
    width: calc(100% - 20px) !important;
    height: calc(100% - 20px) !important;
    position: absolute !important;
    top: 10px !important;
    left: 10px !important;
  }
  
  .certificate-border-inner {
    border: 4px solid #0047AB !important;
    border-bottom: 4px solid #0047AB !important;
    box-sizing: border-box !important;
    width: calc(100% - 40px) !important;
    height: calc(100% - 40px) !important;
    position: absolute !important;
    top: 20px !important;
    left: 20px !important;
    
    /* Ensure inner border content doesn't overflow */
    overflow: hidden !important;
    padding: 20px !important;
  }
  
  /* Ensure borders are visible */
  .certificate-border-outer,
  .certificate-border-middle,
  .certificate-border-inner {
    border-style: solid !important;
    border-width: 7px !important;
    -webkit-print-color-adjust: exact !important;
    print-color-adjust: exact !important;
  }
  
  /* ============================
     SIGNATURE SECTIONS
     Positioned at the bottom of the certificate
     ============================ */
  
  /* Container for both signatures */
  .signature-container {
    display: flex !important;
    justify-content: space-between !important;
    align-items: flex-end !important;
    position: absolute !important;
    bottom: 70px !important; /* Position from bottom of inner border */
    left: 40px !important;
    right: 40px !important;
    z-index: 10 !important;
    pointer-events: none !important;
  }
  
  /* Individual signature box */
  .signature-box {
    flex: 0 0 auto !important;
    width: 45% !important; /* Each takes about half the width */
    text-align: center !important;
  }
  
  /* Signature line (where actual signature would go) */
  .signature-line {
    display: block !important;
    width: 100% !important;
    height: 1px !important;
    background-color: #000 !important;
    margin: 0 auto 8px auto !important;
    position: relative !important;
  }
  
  /* Optional: Add a decorative underline for signature */
  .signature-line::after {
    content: '' !important;
    position: absolute !important;
    bottom: -3px !important;
    left: 0 !important;
    width: 100% !important;
    height: 1px !important;
    background-color: #000 !important;
  }
  
  /* Label text for signatures */
  .signature-label {
    font-size: 14px !important;
    font-weight: bold !important;
    color: #000 !important;
    margin-top: 30px !important; /* Space for actual signature */
    display: block !important;
  }
  
  /* Name/Title under signature line */
  .signature-name {
    font-size: 12px !important;
    color: #333 !important;
    margin-top: 5px !important;
    font-weight: normal !important;
  }
  
  /* Left signature (HOD) */
  .signature-hod {
    text-align: left !important;
  }
  
  /* Right signature (Course Instructor) */
  .signature-instructor {
    text-align: right !important;
  }
  
  /* ============================
     PRINT OPTIMIZATION
     ============================ */
  
  /* Force all colors and backgrounds to print */
  * {
    -webkit-print-color-adjust: exact !important;
    print-color-adjust: exact !important;
    
  }
  
  /* Ensure white background for print */
  .certificate-wrapper,
  .certificate-border-outer,
  .certificate-border-middle,
  .certificate-border-inner {
    background-color: white !important;
  }
  
  /* Remove shadows for clean printing */
  * {
    box-shadow: none !important;
    text-shadow: none !important;
  }
  
  /* Force visibility */
  .certificate-wrapper * {
    visibility: visible !important;
  }
  
  /* Ensure no clipping */
  body {
    overflow: visible !important;
  }





    /* Responsive breakpoints */

    @media screen and (max-width: 1200px) {
        .certificate-wrapper {
            /* transform: scale(0.95); 
            transform-origin: top center;
        }
    }
    
    @media screen and (max-width: 992px) {
        .certificate-wrapper {
            transform: scale(0.9);
        }
        
        .signature-section {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        
        .signature-box {
            width: 100%;
            max-width: 200px;
        }
    }
    
    @media screen and (max-width: 768px) {
        .certificate-wrapper {
            transform: scale(0.85);
        }
        
        .results-table {
            font-size: 0.8rem;
        }
        
        .student-info-table {
            font-size: 0.85rem;
        }
        
        .certificate-header h2 {
            font-size: 1.4rem;
        }
        
        .certificate-header h3 {
            font-size: 1.2rem;
        }
    }
    
    @media screen and (max-width: 576px) {
        .certificate-wrapper {
            transform: scale(0.8);
        }
        
        .results-table {
            display: block;
            overflow-x: auto;
        }
        
        .results-table th,
        .results-table td {
            padding: 4px 8px;
            font-size: 0.75rem;
        }
        
        .certificate-border-outer {
            padding: 10px;
        }
        
        .certificate-border-middle {
            padding: 8px;
        }
        
        .certificate-border-inner {
            padding: 5px;
        }
    }
    
    @media screen and (max-width: 480px) {
        .certificate-wrapper {
            transform: scale(0.75);
        }
        
        .certificate-header img {
            height: 100px;
        }
        
        .certificate-header h2 {
            font-size: 1.1rem;
        }
        
        .certificate-header h3 {
            font-size: 0.9rem;
            font-family: 'Times New Roman', Times, serif;
        }
        
        .signature-section {
            padding: 0 10px;
        }
    }
        */
    
    /* Modal adjustments for responsiveness */
    #uni_modal .modal-dialog {
        max-width: 95% !important;
        width: 95% !important;
        margin: 10px auto !important;
    }
    
    #uni_modal .modal-content {
        height: auto;
        max-height: 95vh;
        overflow-y: auto;
    }
    
    /* Button styles */
    .modal-footer {
        display: flex;
        gap: 10px;
        padding: 15px;
        margin: 0;
    }
    
    /* Remove image outlines */
    img {
        border: none !important;
        outline: none !important;
        box-shadow: none !important;
    }
    
    /* Utility classes */
    .text-center {
        text-align: center !important;
    }
    
    .font-bold {
        font-weight: bold !important;
    }
    
    .mb-1 {
        margin-bottom: 0.1rem !important;
    }
    
    .mb-2 {
        margin-bottom: 1rem !important;
    }
    
    .mb-3 {
        margin-bottom: 1.5rem !important;
    }
</style>

<div class="container-fluid position-relative responsive-container" id="printable">
    <div class="certificate-wrapper">
        <!-- Watermark -->
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ2SPk0LPOPvjiGVdXE7RvPqb8lq6BBP74jVA&s" class="watermark-img" alt="Watermark">
        <style>
</style>

        <div class="certificate-border-outer">
            <div class="certificate-border-middle">
                <div class="certificate-border-inner">
                    <?php
// Assuming $student_id is available in view_result.php
$dept_name = 'N/A'; // default

$student = $conn->query("SELECT department_id FROM students WHERE id = $student_id LIMIT 1")->fetch_assoc();
if($student && !empty($student['department_id'])) {
    $dept_id = $student['department_id'];
    $dept_query = $conn->query("SELECT name FROM departments WHERE id = $dept_id LIMIT 1");
    if($dept_query && $dept_query->num_rows > 0) {
        $dept_name = $dept_query->fetch_assoc()['name'];
    }
}
?>
                    <!-- Header -->
                    <div class="certificate-header">
                        <img src="https://uoli.edu.pk/wp-content/uploads/2024/09/Final-Logo-1-271x300-2.png" alt="UOLi Logo">
                        <h2 style="font-family: 'Times New Roman', Times, serif; font-size: 50px;">University of Loralai</h2>
                        <h1 style="font-family: 'Times New Roman', Times, serif; font-size: 50px;">Department of <b><?php echo ucwords(htmlspecialchars($dept_name)); ?></b></h1>



                    </div>
                    
                    <!-- Student Information -->
                    <table class="student-info-table">
                        <tr>
                            <td style="width: 50%;font-family: 'Times New Roman', Times, serif;font-size: 25px;">Reg istration no: <b><?php echo $student_code ?></b></td>
                            <td width="50%" style="font-family: 'Times New Roman', Times, serif;font-size: 25px;">Semester: <b><?php echo $class ?></b></td>
                        </tr>
                        <tr>
                            <td width="50%" style="font-family: 'Times New Roman', Times, serif;font-size: 25px;">Name: <b><?php echo ucwords($name) ?></b></td>
                            <td width="50%" style="font-family: 'Times New Roman', Times, serif; font-size: 25px;">Department: <b><?php echo ucwords($dept_name ) ?></b></td>
                        </tr>
                    </table>
                    <hr>
                    <!-- Results Table -->
 <!-- Results Table -->
<table class="table table-bordered results-table">
    <thead>
        <tr>
            <th style="font-family: 'Times New Roman', Times, serif; font-size: 25px">Subject Code</th>
            <th style="font-family: 'Times New Roman', Times, serif; font-size: 25px">Subject</th>
            <th style="font-family: 'Times New Roman', Times, serif; font-size: 25px">Total Marks</th>
            <th style="font-family: 'Times New Roman', Times, serif; font-size: 25px">Obtained Marks</th>
            <th style="font-family: 'Times New Roman', Times, serif; font-size: 25px">Subject GPA</th>
            <th style="font-family: 'Times New Roman', Times, serif; font-size: 25px">Percentage</th>  <!-- NEW COLUMN -->
            <th style="font-family: 'Times New Roman', Times, serif; font-size: 25px; font-weight: bold;">Grade</th>   <!-- NEW COLUMN -->
        </tr>
    </thead>
    <tbody>
        <?php 
        $totalMarks = 0;
        $totalSubjects = 0;
        
        $items = $conn->query("
            SELECT r.*, s.subject_code, s.subject 
            FROM result_items r 
            INNER JOIN subjects s ON s.id = r.subject_id 
            WHERE result_id = $id  
            ORDER BY s.subject_code ASC
        ");
        
        while($row = $items->fetch_assoc()):
            $totalMarks += $row['mark'];
            $totalSubjects++;
            // Assume max marks per subject is 100 (can be changed if data available)
            $maxMarks = 100;
            $percentage = ($row['mark'] / $maxMarks) * 100;
            
            // Calculate grade per subject
            if ($percentage < 0 || $percentage > 100) {
                $grade = 'Invalid';
            } elseif ($percentage >= 85) {
                $grade = 'A';
            } elseif ($percentage >= 80) {
                $grade = 'A-';
            } elseif ($percentage >= 75) {
                $grade = 'B+';
            } elseif ($percentage >= 70) {
                $grade = 'B';
            } elseif ($percentage >= 65) {
                $grade = 'B-';
            } elseif ($percentage >= 61) {
                $grade = 'C+';
            } elseif ($percentage >= 58) {
                $grade = 'C';
            } elseif ($percentage >= 55) {
                $grade = 'C-';
            } elseif ($percentage >= 50) {
                $grade = 'D';
            } else {
                $grade = 'F';
            }

            // Calculate GPA per subject (same mapping as overall GPA)
            if ($percentage >= 85 && $percentage <= 100) {
                $subject_gpa = 4.00;
            } elseif ($percentage >= 80) {
                $subject_gpa = 3.70;
            } elseif ($percentage >= 75) {
                $subject_gpa = 3.30;
            } elseif ($percentage >= 70) {
                $subject_gpa = 3.00;
            } elseif ($percentage >= 65) {
                $subject_gpa = 2.70;
            } elseif ($percentage >= 61) {
                $subject_gpa = 2.30;
            } elseif ($percentage >= 58) {
                $subject_gpa = 2.00;
            } elseif ($percentage >= 55) {
                $subject_gpa = 1.70;
            } elseif ($percentage >= 50) {
                $subject_gpa = 1.00;
            } else {
                $subject_gpa = 0.00;
            }
        ?>
        <tr>
            <td style="text-align: center; font-family: 'Times New Roman', Times, serif; font-size: 25px"><?php echo $row['subject_code']; ?></td>
            <td style="text-align: left; font-family: 'Times New Roman', Times, serif; font-size: 25px"><?php echo ucwords($row['subject']); ?></td>
            <td style="text-align: center; font-family: 'Times New Roman', Times, serif; font-size: 25px"><?php echo $maxMarks; ?></td>
            <td style="text-align: center; font-family: 'Times New Roman', Times, serif; font-size: 25px"><?php echo number_format($row['mark']); ?></td>
            <td style="text-align: center; font-family: 'Times New Roman', Times, serif; font-size: 25px"><?php echo number_format($subject_gpa, 2); ?></td>  <!-- NEW COLUMN: subject GPA -->
            <td style="text-align: center; font-family: 'Times New Roman', Times, serif; font-size: 25px"><?php echo number_format($percentage, 2) . '%'; ?></td>   <!-- NEW COLUMN: percentage -->
            <td style="text-align: center; font-family: 'Times New Roman', Times, serif; font-size: 25px; font-weight: bodl;"><?php echo $grade; ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
    
    <?php
    // GPA calculation (same as original)
    $percentage = ($totalSubjects > 0) ? ($totalMarks / ($totalSubjects * 100)) * 100 : 0;
    
    if ($percentage >= 85 && $percentage <= 100) {
        $gpa = 4.00;
    } elseif ($percentage >= 80) {
        $gpa = 3.70;
    } elseif ($percentage >= 75) {
        $gpa = 3.30;
    } elseif ($percentage >= 70) {
        $gpa = 3.00;
    } elseif ($percentage >= 65) {
        $gpa = 2.70;
    } elseif ($percentage >= 61) {
        $gpa = 2.30;
    } elseif ($percentage >= 58) {
        $gpa = 2.00;
    } elseif ($percentage >= 55) {
        $gpa = 1.70;
    } elseif ($percentage >= 50) {
        $gpa = 1.00;
    } else {
        $gpa = 0.00;
    }
    ?>
    
    <tfoot>
        <tr>
            <th style="font-family: 'Times New Roman', Times, serif; font-size: 25px" colspan="6">Total Obtained Marks</th>  <!-- colspan updated from 4 to 6 -->
            <th style="font-family: 'Times New Roman', Times, serif; font-size: 25px;"><?php echo $totalMarks; ?> / <?php echo $totalSubjects * 100; ?></th>
        </tr>
        <tr>
            <th style="font-family: 'Times New Roman', Times, serif; font-size: 25px" colspan="6">GPA</th>  <!-- colspan updated from 4 to 6 -->
            <th style="font-family: 'Times New Roman', Times, serif; font-size: 25px"><?php echo number_format($gpa, 2); ?></th>
        </tr>
    </tfoot>
</table>                    
                    <!-- Signatures -->
                    <div class="signature-section">
                        <div class="signature-box">
                            <br> <br> <br> <br> <br>
                            <p style="margin-bottom: 0; font-weight:bold;">__________________</p>
                            <p style="font-family: 'Times New Roman';font-size: 25px;">Dean</p>
                        </div> 
                        <div class="signature-box">
                            <br> <br> <br> <br>  <br>
                            <p style="margin-bottom: 0; font-weight:bold;">__________________</p>
                            <p style="font-family: 'Times New Roman';font-size: 25px">HOD</p>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer display p-0 m-0">
    <button style="font-family: 'Times New Roman', Times, serif;" type="button" class="btn btn-success" id="print">
        <i class="fa fa-print"></i> Print
    </button>
    <button style="font-family: 'Times New Roman', Times, serif;" type="button" class="btn btn-secondary" data-dismiss="modal">
        Close
    </button>
</div>

<style>
    @media print {
        .modal-footer {
            display: none !important;
        }
    }
    
    @media print {
        .certificate-wrapper {
            transform: none !important;
            height: 700px;
        }   
    }

    #uni_modal .modal-footer{
        display: none
    }
    #uni_modal .modal-footer.display{
        display: flex
    }
</style>

<noscript>
    <style>
        table.table{
            width:100%;
            border-collapse: collapse;
        }
        table.table tr,table.table th, table.table td{
            border:1px solid;
        }
        /* .text-center{
            text-align: center;
        } */
    </style>
</noscript>

<script>
    $('#print').click(function(){
        start_load();
        
        // Get all CSS from current page
        var allStyles = '';
        $('style').each(function() {
            allStyles += $(this).html() + '\n';
        });
        
        // Get the content to print
        var content = $('#printable').html();
        
        // Create complete HTML for print window
        var printContent = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>Print Certificate</title>
                <style>
                    ${allStyles}
    
                    /* Force correct print layout */
                    @media print {

                        @page {
                            size: A4 landscape;
                            margin: 0;
                        }
                        
                        body {
                            margin: 0 !important;
                            padding: 0 !important;
                            width: 297mm !important;
                            height: auto;
                            /* height: 210mm !important; */
                            zoom: 100% !important;
                        }
                        
                        .certificate-wrapper {
                            transform: none !important;
                            width: 100% !important;
                            height: auto !important;    
                            max-width: none !important;
                        }
                        
                        .responsive-container {
                            padding: 0 !important;
                            margin: 0 !important;
                            width: 100% !important;
                        }
                        
                        .watermark-img {
                            opacity: 0.1 !important;
                            display: block !important;
                                
                        }
                    }
                        
                </style>
            </head>
            <body style="margin:0; padding:0;">
                ${content}
            </body>
            </html>
        `;
        var nw = window.open('', '_blank', 'height=900,width=1200');
        nw.document.write(printContent);
        nw.document.close();
        
        // Wait a moment then print
        setTimeout(function() {
            nw.focus();
            nw.print();
            setTimeout(function() {
                nw.close();
                end_load();
            }, 500);
        }, 500);
    });
</script>
<!-- ========== ZOOM CONTROL (SCREEN ONLY) ========== -->
<div style="display: flex; justify-content: center; align-items: center; gap: 15px; margin-top: 15px; padding: 10px; background: #f0f0f0; border-radius: 8px;">
    <button type="button" class="btn btn-sm btn-outline-primary" id="zoomOut">–</button>
    <span style="font-family: 'Times New Roman', Times, serif; font-size: 16px; min-width: 60px; text-align: center;">
        <span id="zoomValue">60</span>%
    </span>
    <button type="button" class="btn btn-sm btn-outline-primary" id="zoomIn">+</button>
    <button type="button" class="btn btn-sm btn-outline-secondary" id="zoomReset">Reset</button>
</div>

<script>
$(document).ready(function(){
    let zoomLevel = 0.6;          // default 60% (changed from 0.8)
    const wrapper = $('.certificate-wrapper');
    const zoomSpan = $('#zoomValue');

    function applyZoom() {
        wrapper.css('transform', 'scale(' + zoomLevel + ')');
        wrapper.css('transform-origin', 'top center');
        zoomSpan.text(Math.round(zoomLevel * 100));
    }

    $('#zoomIn').click(function(){
        zoomLevel = Math.min(zoomLevel + 0.1, 2.0);   // max 200%
        applyZoom();
    });

    $('#zoomOut').click(function(){
        zoomLevel = Math.max(zoomLevel - 0.1, 0.5);   // min 10%
        applyZoom();
    });

    $('#zoomReset').click(function(){
        zoomLevel = 0.6;          // reset to 60% (changed from 0.8)
        applyZoom();
    });

    // Apply the initial zoom (60%)
    applyZoom();
});
</script>