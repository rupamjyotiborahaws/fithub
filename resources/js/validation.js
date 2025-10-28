
export function validate_member_reg_form(form) {
    let name = form.find('input[name="name"]').val().trim();
    let email = form.find('input[name="email"]').val().trim();
    let phone = form.find('input[name="phone"]').val().trim();
    let dob = form.find('input[name="dob"]').val().trim();
    let membership_type = form.find('select[name="membership_type"]').val();
    let gender = form.find('select[name="gender"]').val();
    let payment_method = form.find('select[name="payment_method"]').val();
    let address = form.find('input[name="address"]').val().trim();

    if (name === '' || name.length === 0 || name === null || name === undefined) {
        return [false, "Name is required."];
    }
    if (email === '' || email.length === 0 || email === null || email === undefined) {
        return [false, "Email is required."];
    }
    // Simple email regex for validation
    let email_regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!email_regex.test(email)) {
        return [false, "Invalid email format."];
    }
    if (address === '' || address.length === 0 || address === null || address === undefined) {
        return [false, "Address is required."];
    }
    if (dob === '') {
        return [false, "Date of Birth is required."];
    }
    if (phone === '' || phone.length === 0 || phone === null || phone === undefined) {
        return [false, "Phone number is required."];
    }
    if (phone.length < 10 || phone.length > 10) {
        return [false, "Phone number must be 10 digits."];
    }
    if (membership_type === null || membership_type === '' || membership_type === '0' || membership_type === 's' || membership_type === undefined) {
        return [false, "Select a membership."];
    }
    if (gender === null || gender === '' || gender === '0' || gender === 's' || gender === undefined) {
        return [false, "Select gender."];
    }
    if($('.one_time_single').hasClass('d-none') === false){
        if($('#confirm_one_time_fee_payment').is(':checked') === false) {
            return [false, "Please confirm fee amount collection."];
        }
        if (payment_method === null || payment_method === '' || payment_method === '0' || payment_method === undefined) {
            return [false, "Select a payment method."];
        }
    }
    return [true, ""]; // Form is valid
}

export function validate_progress_record_entry_form(form) {
    let weight = form.find('input[name="weight"]').val().trim();
    let height = form.find('input[name="height"]').val().trim();
    let bmi = form.find('input[name="bmi"]').val().trim();
    let body_fat = form.find('input[name="body_fat"]').val().trim();
    let muscle_mass = form.find('input[name="muscle_mass"]').val().trim();
    let west_cir = form.find('input[name="west_cir"]').val().trim();
    let hip_cir = form.find('input[name="hip_cir"]').val().trim();
    let chest_cir = form.find('input[name="chest_cir"]').val().trim();
    let thigh_cir = form.find('input[name="thigh_cir"]').val().trim();
    let arm_cir = form.find('input[name="arm_cir"]').val().trim();

    if(weight === '' || isNaN(weight) || parseFloat(weight) <= 0) {
        return [false, "Please enter a valid weight."];
    }
    if(height === '' || isNaN(height) || parseFloat(height) <= 0) {
        return [false, "Please enter a valid height."];
    }
    if(bmi === '' || isNaN(bmi) || parseFloat(bmi) <= 0) {
        return [false, "Please enter a valid BMI."];
    }
    if(body_fat === '' || isNaN(body_fat) || parseFloat(body_fat) <= 0) {
        return [false, "Please enter a valid body fat percentage."];
    }
    if(muscle_mass === '' || isNaN(muscle_mass) || parseFloat(muscle_mass) <= 0) {
        return [false, "Please enter a valid muscle mass percentage."];
    }
    if(west_cir === '' || isNaN(west_cir) || parseFloat(west_cir) < 0) {
        return [false, "Please enter a valid waist circumference."];
    }
    if(hip_cir === '' || isNaN(hip_cir) || parseFloat(hip_cir) < 0) {
        return [false, "Please enter a valid hip circumference."];
    }
    if(chest_cir === '' || isNaN(chest_cir) || parseFloat(chest_cir) < 0) {
        return [false, "Please enter a valid chest circumference."];
    }
    if(thigh_cir === '' || isNaN(thigh_cir) || parseFloat(thigh_cir) < 0) {
        return [false, "Please enter a valid thigh circumference."];
    }
    if(arm_cir === '' || isNaN(arm_cir) || parseFloat(arm_cir) < 0) {
        return [false, "Please enter a valid arm circumference."];
    }
    return [true, ""]; // Form is valid
}

export function validate_trainer_reg_form(form) {
    let name = form.find('input[name="name"]').val().trim();
    let email = form.find('input[name="email"]').val().trim();
    let phone = form.find('input[name="phone"]').val().trim();
    let dob = form.find('input[name="dob"]').val().trim();
    let gender = form.find('select[name="gender"]').val();
    let address = form.find('input[name="address"]').val().trim();
    let height = form.find('input[name="height"]').val().trim();
    let weight = form.find('input[name="weight"]').val().trim();

    if (name === '' || name.length === 0 || name === null || name === undefined) {
        return [false, "Name is required."];
    }
    if (email === '' || email.length === 0 || email === null || email === undefined) {
        return [false, "Email is required."];
    }
    // Simple email regex for validation
    let email_regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!email_regex.test(email)) {
        return [false, "Invalid email format."];
    }
    if (address === '' || address.length === 0 || address === null || address === undefined) {
        return [false, "Address is required."];
    }
    if (dob === '') {
        return [false, "Date of Birth is required."];
    }
    if (phone === '' || phone.length === 0 || phone === null || phone === undefined) {
        return [false, "Phone number is required."];
    }
    if (phone.length < 10 || phone.length > 10) {
        return [false, "Phone number must be 10 digits."];
    }
    if (gender === null || gender === '' || gender === '0' || gender === 's' || gender === undefined) {
        return [false, "Select gender."];
    }
    if (height === null || height === '' || height === '0' || height === 's' || height === undefined) {
        return [false, "Height of the trainer is required."];
    }
    if (weight === null || weight === '' || weight === '0' || weight === 's' || weight === undefined) {
        return [false, "Weight of the trainer is required."];
    }
    return [true, ""]; // Form is valid
}


