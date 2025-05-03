<?php
/**
 * Template Name: Job Submission Form
 */

get_header();
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap');

body {
    font-family: 'Poppins', sans-serif;
}

.form-wrapper {
    max-width: 700px;
    margin: 40px auto;
    padding: 40px 30px;
    background: #fff;
    border: 1px solid #0a6b8d;
    box-shadow: 0 10px 40px -5px rgba(0,0,0,0.15);
}

.form-wrapper h1 {
    font-size: 28px;
    margin-bottom: 30px;
    font-weight: 600;
    color: #333;
}

.section-title {
    font-size: 20px;
    font-weight: 600;
    margin-top: 30px; 
    margin-bottom: 30px; 
    color: #0a6b8d;
    border-bottom: 1px solid #ddd;
    padding-bottom: 5px;
    font-family: Balgin bold; 
}

.form-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}

.form-group {
    width: 100%;
}

.form-group.half {
    width: calc(50% - 10px);
}

.form-wrapper label {
    display: block;
    font-weight: 500;
    color: #333;
    font-size: 16px;
    font-weight: 300; 
    margin-bottom: 10px;
}

.form-wrapper input[type="text"],
.form-wrapper input[type="email"],
.form-wrapper input[type="url"],
.form-wrapper input[type="file"],
.form-wrapper select {
    width: 100%;
    padding: 12px 14px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 16px;
    font-family: 'Poppins', sans-serif;
    font-weight: 300;
    box-sizing: border-box;
}

.form-wrapper .wp-editor-wrap {
    margin-bottom: 20px;
}

.form-wrapper input[type="submit"] {
    background-color: #0a6b8d;
    color: #fff;
    padding: 14px;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    width: 100%;
    font-weight: 500;
    margin-top: 20px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    font-family: Balgin bold; 
}

.form-wrapper input[type="submit"]:hover {
    background-color: #0884CC;
}
</style>

<<div class="form-wrapper">
    <h1>🚀 Post a job!</h1>
    <p>📝 Fill in the details below related to your job posting! You’ll receive an invoice by email only after the job is published.</p>
    <p><strong>⏱️ No time?</strong></p>
    <p>📎 You can also send the job description as a link, PDF, or Word file to <a href="mailto:support@sustainablejobs.nl">support@sustainablejobs.nl</a>.</p>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verzenden'])) {
        $first_name = sanitize_text_field($_POST['first_name']);
        $last_name = sanitize_text_field($_POST['last_name']);
        $package = sanitize_text_field($_POST['package']);
        $company_name = sanitize_text_field($_POST['company_name']);
        $location = sanitize_text_field($_POST['location']);
        $job_description = wp_kses_post($_POST['job_description']);
        $additional_info = wp_kses_post($_POST['additional_info']);
        $referral = sanitize_text_field($_POST['referral']);
        $email = sanitize_email($_POST['email']);

        $attachments = [];
        if (!empty($_FILES['company_logo']['tmp_name'])) {
            $uploaded_file = wp_handle_upload($_FILES['company_logo'], ['test_form' => false]);
            if (!isset($uploaded_file['error'])) {
                $attachments[] = $uploaded_file['file'];
            }
        }

        $message = "New job submitted via the website:\n\n";
        $message .= "First name: $first_name\n";
        $message .= "Last name: $last_name\n";
        $message .= "Selected package: $package\n";
        $message .= "Company name: $company_name\n";
        $message .= "Location: $location\n";
        $message .= "Email: $email\n";
        $message .= "How did you hear about us?: $referral\n\n";
        $message .= "--- Job description ---\n" . strip_tags($job_description) . "\n\n";
        $message .= "--- Additional information ---\n" . strip_tags($additional_info) . "\n";

        wp_mail('support@sustainablejobs.nl', 'New job submitted via form', $message, ['Content-Type: text/plain; charset=UTF-8'], $attachments);

        echo "<p><strong>Thank you! Your job has been submitted successfully.</strong></p>";
    } else {
    ?>

    <form method="post" action="" enctype="multipart/form-data">

        <!-- SECTION 1: Choose your package -->
        <div class="section-title">Choose your package</div>
        <div class="form-grid">
            <div class="form-group">
                <label for="package">Package</label>
                <select name="package" id="package" required>
                    <option value="">Select a package</option>
                    <option value="Basic Package">Freemiun</option>
                    <option value="Standard Package">Standard Listing – $275 USD</option>
                    <option value="Premium Package">Featuered Listing – $375 USD</option>
                </select>
            </div>
        </div>

        <!-- SECTION 2: Contact details -->
        <div class="section-title">Contact details</div>
        <div class="form-grid">
            <div class="form-group half">
                <label for="first_name">First name</label>
                <input type="text" name="first_name" id="first_name" required>
            </div>
            <div class="form-group half">
                <label for="last_name">Last name</label>
                <input type="text" name="last_name" id="last_name" required>
            </div>

            <div class="form-group half">
                <label for="company_name">Company name</label>
                <input type="text" name="company_name" id="company_name" required>
            </div>
            <div class="form-group half">
                <label for="email">Email address</label>
                <input type="email" name="email" id="email" required>
            </div>
        </div>

        <!-- SECTION 3: Job information -->
        <div class="section-title">Job information</div>
        <div class="form-grid">
            <div class="form-group">
                <label for="location">Job location</label>
                <input type="text" name="location" id="location" required>
            </div>

            <div class="form-group">
                <label for="job_description">Job description</label>
                <?php
                wp_editor('', 'job_description', [
                    'textarea_name' => 'job_description',
                    'media_buttons' => false,
                    'textarea_rows' => 10,
                    'editor_class' => 'rich-editor'
                ]);
                ?>
            </div>

            <div class="form-group">
                <label for="additional_info">Additional information</label>
                <?php
                wp_editor('', 'additional_info', [
                    'textarea_name' => 'additional_info',
                    'media_buttons' => false,
                    'textarea_rows' => 6,
                    'editor_class' => 'rich-editor'
                ]);
                ?>
            </div>

            <div class="form-group">
                <label for="company_logo">Company logo</label>
                <input type="file" name="company_logo" id="company_logo" accept="image/*">
            </div>
        </div>

        <!-- SECTION 4: Help Us -->
        <div class="section-title">Help Us</div>
        <div class="form-grid">
            <div class="form-group">
                <label for="referral">How did you hear about us?</label>
                <input type="text" name="referral" id="referral">
            </div>
        </div>

        <input type="submit" name="verzenden" value="Submit your job">
    </form>

    <?php } ?>
</div>

<?php get_footer(); ?>
