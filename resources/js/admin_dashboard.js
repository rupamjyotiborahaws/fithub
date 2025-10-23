import $ from 'jquery';
window.$ = $;
window.jQuery = $;

import toastr from 'toastr';
window.toastr = toastr;

// Optionally import Toastr CSS too
import 'toastr/build/toastr.min.css';

$(document).ready(function() {
        $("#loader").hide();
        let selectedPayments = [];
        let selectedMembersPayments = [];
        let fee_collections_filter =  {
                fee_month: 0,
                membership_id: 0
        };
        const baseUrl = window.location.origin;
        const all_months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        const registrationChartId = 'registrationChart';
        const currentMonthFeeCollectionChartId = 'currentMonthFeeCollectionChart';
        const trainerAllotmentChartId = 'trainerAllotmentChart';
        const attendanceChartId = 'todayAttendanceChart';
        const progressChartCanvasId = 'progressChartCanvasId';
        const feeCollectionsDisplayChartCanvasId = 'feeCollectionsDisplayChartCanvasId';

        
        // Store chart instances for proper cleanup
        let chartInstances = {};

        function loadRegistrationData() {
                $.ajax({
                        url: baseUrl + '/api/registrations', // adjust to your backend route
                        method: 'GET',
                        dataType: 'json',
                        success: function (data) {
                                console.log('Fetched registration data:', data);
                                if (!data || !Array.isArray(data.months) || !Array.isArray(data.registrations)) {
                                        console.error('Unexpected response shape', data);
                                        return;
                                }
                                const chartElement = document.getElementById(registrationChartId);
                                if(chartElement) {
                                        const chartInstance = window.renderRegistrationChart(registrationChartId, data.months, data.registrations);
                                        if (chartInstance) {
                                                chartInstances[registrationChartId] = chartInstance;
                                        }
                                } else {
                                        console.error('Chart element not found:', registrationChartId);
                                }
                        },
                        error: function (xhr, status, err) {
                                console.error('Error fetching registration data:', status, err, xhr.responseText);
                        }
                });
        }

        function displayFeeCollectionsChart(data, filter) {
                if (!data || !Array.isArray(data.label) || !Array.isArray(data.values)) {
                        console.error('Unexpected response shape', data);
                        return;
                }
                const chartElement = document.getElementById(feeCollectionsDisplayChartCanvasId);
                if(chartElement) {
                        // Clear existing chart before creating new one
                        if (chartInstances[feeCollectionsDisplayChartCanvasId]) {
                                chartInstances[feeCollectionsDisplayChartCanvasId].destroy();
                                delete chartInstances[feeCollectionsDisplayChartCanvasId];
                        }
                        // Clear the canvas
                        const ctx = chartElement.getContext('2d');
                        ctx.clearRect(0, 0, chartElement.width, chartElement.height);
                        const chartInstance = window.renderFeeCollectionsChart(feeCollectionsDisplayChartCanvasId, data.label, data.values, filter);
                        if (chartInstance) {
                                chartInstances[feeCollectionsDisplayChartCanvasId] = chartInstance;
                        }
                } else {
                        console.error('Chart element not found:', feeCollectionsDisplayChartCanvasId);
                }
        }

        function loadCurrentMonthFeeCollectionData() {
                console.log('Loading current month fee collection data...');
                $.ajax({
                        url: baseUrl + '/api/current_month_fee_collections', // adjust to your backend route
                        method: 'GET',
                        dataType: 'json',
                        success: function (data) {
                                console.log('Fetched fee collection data:', data);
                                if (!data || !Array.isArray(data.months) || !Array.isArray(data.fee_collections)) {
                                        console.error('Unexpected response shape', data);
                                        return;
                                }
                                const chartElement = document.getElementById(currentMonthFeeCollectionChartId);
                                
                                if (chartElement) {
                                        const currentMonth = new Date().toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
                                        console.log('Calling renderFeeCollectionChart with:', {
                                                canvasId: currentMonthFeeCollectionChartId,
                                                labels: data.months,
                                                values: data.fee_collections,
                                                month: currentMonth
                                        });
                                        const chartInstance = window.renderFeeCollectionChart(currentMonthFeeCollectionChartId, data.months, data.fee_collections, currentMonth);
                                        if (chartInstance) {
                                                chartInstances[currentMonthFeeCollectionChartId] = chartInstance;
                                        }
                                } else {
                                        console.error('Chart element not found:', currentMonthFeeCollectionChartId);
                                }
                        },
                        error: function (xhr, status, err) {
                                console.error('Error fetching fee collection data:', status, err, xhr.responseText);
                        }
                });
        }

        function loadTodayAttendanceData() {
                console.log('Loading today attendance data...');
                $.ajax({
                        url: baseUrl + '/api/today_attendance', // adjust to your backend route
                        method: 'GET',
                        dataType: 'json',
                        success: function (data) {
                                console.log('Fetched today attendance data:', data);
                                if (!data || !Array.isArray(data.status) || !Array.isArray(data.attendance)) {
                                        console.error('Unexpected response shape', data);
                                        return;
                                }
                                const chartElement = document.getElementById(attendanceChartId);
                                console.log('Chart element found:', chartElement);
                                console.log('renderTodayAttendanceChart function available:', typeof window.renderTodayAttendanceChart);
                                
                                if (chartElement) {
                                        console.log('Calling renderTodayAttendanceChart with:', {
                                                canvasId: attendanceChartId,
                                                labels: data.status,
                                                values: data.attendance,
                                        });
                                        const chartInstance = window.renderTodayAttendanceChart(attendanceChartId, data.status, data.attendance);
                                        if (chartInstance) {
                                                chartInstances[attendanceChartId] = chartInstance;
                                        }
                                } else {
                                        console.error('Chart element not found:', attendanceChartId);
                                }
                        },
                        error: function (xhr, status, err) {
                                console.error('Error fetching fee collection data:', status, err, xhr.responseText);
                        }
                });
        }

        function loadTrainerAllotmentData() {
                $.ajax({
                        url: baseUrl + '/api/trainer_allotments', // adjust to your backend route
                        method: 'GET',
                        dataType: 'json',
                        success: function (data) {
                                console.log('Fetched alloted data:');
                                console.log(data);
                                if (!data || !Array.isArray(data.trainers) || !Array.isArray(data.allotments)) {
                                        console.error('Unexpected response shape', data);
                                        return;
                                }
                                const chartElement = document.getElementById(trainerAllotmentChartId);
                                if(chartElement) {
                                        const chartInstance = window.renderMemberTrainerChart(trainerAllotmentChartId, data.trainers, data.allotments);
                                        if (chartInstance) {
                                                chartInstances[trainerAllotmentChartId] = chartInstance;
                                        }
                                } else {
                                        console.error('Chart element not found:', trainerAllotmentChartId);
                                }
                        },
                        error: function (xhr, status, err) {
                                console.error('Error fetching registration data:', status, err, xhr.responseText);
                        }
                });
        }

        function loadRecentFeeCollectionData(selectedMonth) {
                console.log('Loading recent fee collection data for month:', selectedMonth);
                $.ajax({
                        url: baseUrl + '/api/recent_fee_collections', // adjust to your backend route
                        method: 'GET',
                        dataType: 'json',
                        data: { month: selectedMonth },
                        success: function (data) {
                                console.log('Fetched fee collection data:', data);
                                if (!data || !Array.isArray(data.months) || !Array.isArray(data.fee_collections)) {
                                        console.error('Unexpected response shape', data);
                                        return;
                                }
                                const chartElement = document.getElementById(currentMonthFeeCollectionChartId);
                                console.log('Chart element found:', chartElement);
                                console.log('renderFeeCollectionChart function available:', typeof window.renderFeeCollectionChart);
                                
                                if (chartElement) {
                                        // Clear existing chart before creating new one
                                        if (chartInstances[currentMonthFeeCollectionChartId]) {
                                                console.log('Destroying existing chart instance');
                                                chartInstances[currentMonthFeeCollectionChartId].destroy();
                                                delete chartInstances[currentMonthFeeCollectionChartId];
                                        }
                                        
                                        // Clear the canvas
                                        const ctx = chartElement.getContext('2d');
                                        ctx.clearRect(0, 0, chartElement.width, chartElement.height);
                                        
                                        // Create new chart and store the instance
                                        const chartInstance = window.renderFeeCollectionChart(currentMonthFeeCollectionChartId, data.months, data.fee_collections, selectedMonth);
                                        if (chartInstance) {
                                                chartInstances[currentMonthFeeCollectionChartId] = chartInstance;
                                                $('#chartHeader').text(`Fee Collection status for the month of ${selectedMonth}`);
                                        }
                                } else {
                                        console.error('Chart element not found:', currentMonthFeeCollectionChartId);
                                }
                        },
                        error: function (xhr, status, err) {
                                console.error('Error fetching fee collection data:', status, err, xhr.responseText);
                        }
                });
        }

        function loadRecentRegistrations(data) {
                console.log('Fetched registration data:', data);
                if (!data || !Array.isArray(data.months) || !Array.isArray(data.registrations)) {
                        console.error('Unexpected response shape', data);
                        return;
                }
                const chartElement = document.getElementById(registrationChartId);
                console.log('Chart element found:', chartElement);
                console.log('renderRegistrationChart function available:', typeof window.renderRegistrationChart);
                if (chartElement) {
                        // Clear existing chart before creating new one
                        if (chartInstances[registrationChartId]) {
                                console.log('Destroying existing chart instance');
                                chartInstances[registrationChartId].destroy();
                                delete chartInstances[registrationChartId];
                        }
                        // Clear the canvas
                        const ctx = chartElement.getContext('2d');
                        ctx.clearRect(0, 0, chartElement.width, chartElement.height);
                        // Create new chart and store the instance
                        const chartInstance = window.renderRegistrationChart(registrationChartId, data.months, data.registrations);
                        if (chartInstance) {
                                chartInstances[registrationChartId] = chartInstance;
                                //$('#chartHeader').text(`Registration status for the month of ${selectedMonth}`);
                        }
                }
        }

        function loadMemberProgressChart(matric, member_id) {
                $.ajax({
                        url: baseUrl + '/api/fetch_member_progress/' + member_id + '/' + matric, // adjust to your backend route
                        method: 'GET',
                        dataType: 'json',
                        success: function (response) {
                                let data = response.data;
                                if (!data || !Array.isArray(data.measure_dates) || !Array.isArray(data.measurement_values)) {
                                        console.error('Unexpected response shape', response);
                                        return;
                                }
                                const chartElement = document.getElementById(progressChartCanvasId);
                                if(chartElement) {
                                        // Clear existing chart before creating new one
                                        if (chartInstances[progressChartCanvasId]) {
                                                chartInstances[progressChartCanvasId].destroy();
                                                delete chartInstances[progressChartCanvasId];
                                        }
                                        // Clear the canvas
                                        const ctx = chartElement.getContext('2d');
                                        ctx.clearRect(0, 0, chartElement.width, chartElement.height);

                                        const chartInstance = window.renderMemberProgressChart(progressChartCanvasId, data.measure_dates, data.measurement_values, matric);
                                        if (chartInstance) {
                                                chartInstances[progressChartCanvasId] = chartInstance;
                                        }
                                } else {
                                        console.error('Chart element not found:', progressChartCanvasId);
                                }
                        },
                        error: function (xhr, status, err) {
                                console.error('Error fetching registration data:', status, err, xhr.responseText);
                        }
                });
        }
        
        // Make function globally available
        window.loadCurrentMonthFeeCollectionData = loadCurrentMonthFeeCollectionData;
        window.loadTrainerAllotmentData = loadTrainerAllotmentData;

        // Initial load
        loadRegistrationData();

        // Load today attendance chart if the element exists (dashboard page)
        loadTodayAttendanceData();
        
        // Only load fee collection chart if the element exists (fee collection page)
        if (document.getElementById(currentMonthFeeCollectionChartId)) {
                loadCurrentMonthFeeCollectionData();
        }

        // Load trainer allotment chart if the element exists (trainer allotment page)
        if (document.getElementById(trainerAllotmentChartId)) {
                loadTrainerAllotmentData();
        }

        //Load members with pending fees
        function loadMembersWithPendingFees() {
            $.ajax({
                url: baseUrl + '/api/members_with_pending_fees',
                method: 'GET',
                success: function(response) {
                        console.log('Members with pending fees:', response);
                        if(response && response.status === 'success') {
                                console.log('Rendering members with pending fees:', response.data);
                                const data = response.data;
                                let displayTxt = '';
                                Object.keys(data).forEach(memberId => {
                                        const item = response.data[memberId];
                                        let name = item.name.length > 20 ? item.name.substring(0, 17) + '...' : item.name;
                                        let pending_fees = item.pending_fees;
                                        let pending_fee_months = pending_fees.map(fee => fee.for_month).join(', ');
                                        displayTxt += `<div style="padding:8px; border-bottom:1px solid #ddd;">
                                                <a href="${baseUrl}/admin/${memberId}" style="text-decoration:none; font-weight:bold;">${name}</a>
                                                <br/>
                                                <span style="font-size: 0.9em; color: #555;">Pending Fees for: ${pending_fee_months}</span>
                                        </div>`;
                                });
                                $('.pending_fee_members').html(displayTxt);
                        } else {
                                console.error('Unexpected response shape', response);
                        }
                },
                error: function(xhr, status, err) {
                    console.error('Error fetching members with pending fees:', status, err, xhr.responseText);
                }
            });
        }

        function loadRecentRegistrationsData(selectedMonth) {
                $.ajax({
                        url: baseUrl + '/api/recent_registrations',
                        method: 'GET',
                        data: { months: selectedMonth },
                        success: function(response) {
                                console.log('Recent registrations data:', response);
                                if(response && response.status === 'success') {
                                        loadRecentRegistrations(response.data);
                                } else {
                                        console.error('Unexpected response shape', response);
                                }
                        },
                        error: function(xhr, status, err) {
                                console.error('Error fetching recent registrations:', status, err, xhr.responseText);
                        }
                });
        }

        loadMembersWithPendingFees();

        // Handle recent fee collection radio button change
        $(document).on('change', '.recent_fee_collection', function() {
            const selectedMonth = $(this).val();
            console.log('Selected recent fee collection month:', selectedMonth);
            // Load data for the selected month
            loadRecentFeeCollectionData(selectedMonth);
        });

        //Handle recent registrations radio button change
        $(document).on('change', '.recent_registrations', function() {
            const selectedMonth = $(this).val();
            console.log('Selected recent registrations month:', selectedMonth);
            // Load data for the selected month
            loadRecentRegistrationsData(selectedMonth);
        });

        // Show one time amount when selected from membership dropdown
        $(document).on('change', '.membership_type', function() {
                const selectedOption = $(this).val();
                console.log('Selected membership option:', selectedOption);
                const membership = membershipData.find(m => m.id == selectedOption);
                console.log('Matched membership data:', membership);
                if(membership) {
                        const oneTimeAmount = membership.one_time_fee;
                        const payment_type = membership.payment_type;
                        console.log('One time amount:', oneTimeAmount, 'Payment type:', payment_type);
                        if (payment_type === 'single') {
                        $('.one_time_amount').val(oneTimeAmount);
                        $('.one_time_single').removeClass('d-none');
                        $('.one_time_amount').prop('disabled', true);
                        } else {
                        $('.one_time_amount').val('');
                        $('.one_time_single').addClass('d-none');
                        }
                        const membership_time_schedule = membershipTimeSchedule.filter(m => m.membership_id == selectedOption);
                        if(membership_time_schedule && membership_time_schedule.length > 0) {
                                let scheduleInputs = ``;
                                membership_time_schedule.forEach((schedule, index) => {
                                        let count = index + 1;
                                        scheduleInputs += `<div class="row mt-2" id="time_schedule_div_${count}">
                                        <div class="col-md-3 col-lg-3 col-sm-6 col-xs-6">
                                                <input type="time" class="form-control schedule_time_selection" id="time_schedule_date_${count}" value="${schedule.start_time}" disabled>
                                        </div>
                                        <div class="col-md-3 col-lg-3 col-sm-6 col-xs-6">
                                                <input type="radio" id="time_schedule_${count}" name="time_schedule" style="margin-top:12px; font-size:30px;" value="${schedule.id}">
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-sm-4 col-xs-4">

                                        </div></div>`;
                                });
                                $('.time_schedules_container').html(scheduleInputs);
                                $('.time_schedules_container_display').removeClass('d-none');
                        } else {
                                //$('.time_schedules_container').html('<span style="color:#555;">No time schedules defined for this membership.</span>');
                                $('.time_schedules_container').html('');
                                $('.time_schedules_container_display').addClass('d-none');
                        }
                } else {
                        $('.one_time_amount').val('');
                        $('.one_time_single').addClass('d-none');
                }
        });

        // Confirm one time fee payment checkbox
        $(document).on('change', '#confirm_one_time_fee_payment', function() {
            const isChecked = $(this).is(':checked');
            console.log('One time fee payment confirmed:', isChecked);
            if(isChecked) {
                $('.payment_type_details').removeClass('d-none');
            } else {
                $('.payment_type_details').addClass('d-none');
            }
        });

        // New member registration
        $('.register_member').click(function(e) {
                e.preventDefault();
                $('.loadercontent').html('Registering member, please wait...');
                $("#loader").show();
                const form = $(this).closest('form');
                $.ajax({
                        url: baseUrl + '/api/register_member',
                        method: 'POST',
                        data: form.serialize(),
                        success: function(response) {
                                $('.loadercontent').html('');
                                $("#loader").hide();
                                toastr.options.timeOut = 5000;
                                toastr.success(response.message || "Member registered successfully");
                                form[0].reset();
                                $('input[name="time_schedule"]').prop('checked', false);
                                location.reload();
                        },
                        error: function(xhr, status, err) {
                                toastr.options.timeOut = 5000;
                                toastr.error("Error registering member");
                                //console.error('Error registering member:', status, err, xhr.responseText);
                        }
                });
        });
        //Client data update
        $('.register_client').click(function(e) {
                e.preventDefault();
                $('.loadercontent').html('Updating client data, please wait...');
                $("#loader").show();
                const form = $(this).closest('form');
                $.ajax({
                        url: baseUrl + '/api/addupdate_client',
                        method: 'POST',
                        data: form.serialize(),
                        success: function(response) {
                                $('.loadercontent').html('');
                                $("#loader").hide();
                                toastr.options.timeOut = 5000;
                                toastr.success(response.message || "Client data updated successfully");
                                if(response.status == 'updated') {
                                        form[0].name = response.data.name;
                                        form[0].email = response.data.email;
                                        form[0].address = response.data.address;
                                        form[0].phone_no = response.data.phone_no;
                                        form[0].date_of_icorporation = response.data.date_of_icorporation;
                                        form[0].contact_person = response.data.contact_person;
                                        form[0].gst_id = response.data.gst_id;
                                        form[0].business_type = response.data.business_type;
                                }
                                form[0].reset();
                        },
                        error: function(xhr, status, err) {
                                toastr.options.timeOut = 5000;
                                toastr.error("Error updating client data");
                                //console.error('Error registering member:', status, err, xhr.responseText);
                        }
                });
        });

        // select fees based on payment type in membership form
        $('.payment_type').change(function() {
                if ($('.payment_type[value="single"]').is(':checked')) {
                        $('.addm').addClass('d-none');
                        $('.monthly').addClass('d-none');
                        $('.one_time').removeClass('d-none');
                } else {
                        $('.monthly').removeClass('d-none');
                        $('.addm').removeClass('d-none');
                        $('.one_time').addClass('d-none');
                }
        });

        // select fees based on payment type in membership form for edit
        $('.edit_payment_type').change(function() {
                if ($('.edit_payment_type[value="single"]').is(':checked')) {
                        $('.addm_edit').addClass('d-none');
                        $('.monthly_edit').addClass('d-none');
                        $('.one_time_edit').removeClass('d-none');
                } else {
                        $('.monthly_edit').removeClass('d-none');
                        $('.addm_edit').removeClass('d-none');
                        $('.one_time_edit').addClass('d-none');
                }
        });

        //Add new membership plan
        $('.add_membership').click(function(e) {
                e.preventDefault();
                $('.loadercontent').html('Adding new membership plan, please wait...');
                $("#loader").show();
                const form = $(this).closest('form');
                $.ajax({
                        url: baseUrl + '/api/add_membership',
                        method: 'POST',
                        data: form.serialize(),
                        success: function(response) {
                                $('.loadercontent').html('');
                                $("#loader").hide();
                                toastr.options.timeOut = 5000;
                                toastr.success(response.message || "Membership plan added successfully");
                                form[0].reset();
                                setTimeout(() => {
                                        location.reload();
                                }, 3000);
                        },
                        error: function(xhr, status, err) {
                                toastr.options.timeOut = 5000;
                                toastr.error("Error adding membership plan");
                        }
                });
        });

        // Handle edit button click to populate edit modal
        $(document).on('click', '[data-bs-target="#editMembershipModal"]', function() {
                const button = $(this);
                const id = button.data('id');
                const type = button.data('type');
                const duration = button.data('duration');
                const payment_type = button.data('payment_type');
                const admission_fee = button.data('admission_fee');
                const monthly_fee = button.data('monthly_fee');
                const one_time_fee = button.data('one_time_fee');
                const benefits = button.data('benefits');
                const description = button.data('description');
                const is_active = button.data('is_active');
                const is_transferable = button.data('is_transferable');
                const time_schedules = button.data('time_schedules');
                
                // Populate the edit modal form fields
                const editModal = $('#editMembershipModal');
                editModal.find('input[name="type"]').val(type);
                editModal.find('input[name="duration_months"]').val(duration);
                editModal.find('input[name="edit_adm_fee"]').val(admission_fee);
                editModal.find('input[name="edit_monthly_fee"]').val(monthly_fee);
                editModal.find('input[name="edit_one_time_fee"]').val(one_time_fee);
                editModal.find('textarea[name="benefits"]').val(benefits);
                editModal.find('textarea[name="description"]').val(description);
                if(payment_type === 'single') {
                        $('.addm_edit').addClass('d-none');
                        $('.monthly_edit').addClass('d-none');
                        $('.one_time_edit').removeClass('d-none');
                        editModal.find('input[type="radio"][value="single"]').prop('checked', true);
                } else {
                        $('.monthly_edit').removeClass('d-none');
                        $('.addm_edit').removeClass('d-none');
                        $('.one_time_edit').addClass('d-none');
                        editModal.find('input[type="radio"][value="recurring"]').prop('checked', true);
                }
                if(is_active) {
                        editModal.find('input[name="is_active"]').prop('checked', true);
                } else {
                        editModal.find('input[name="is_active"]').prop('checked', false);
                }
                if(is_transferable) {
                        editModal.find('input[name="is_transferable"]').prop('checked', true);
                } else {
                        editModal.find('input[name="is_transferable"]').prop('checked', false);
                }
                if(time_schedules != 'null' && time_schedules != '') {
                        console.log("Time schedules : " + time_schedules);
                        editModal.find('.time_schedule_edit').removeClass('d-none');
                        editModal.find('input[name="is_edit_time_schedule_required"]').prop('checked', true);
                        $('.time_schedule_edit').empty();
                        const schedules = time_schedules.split(',');
                        console.log("Schedules : " +schedules);
                        let scheduleInputs = '';
                        schedules.reverse();
                        schedules.forEach((time, index) => {
                                let count = index + 1;
                                scheduleInputs += `<div class="row mt-2" id="time_schedule_edit_div_${count}">
                                        <div class="col-md-6">
                                                <input type="time" style="width:100%;" class="form-control" id="time_schedule_edit_${count}" name="time_schedules_edit[]" value="${time}">
                                        </div>
                                        <div class="col-md-6">
                                                <a class="mt-1 remove_time_schedule_edit" style="text-decoration:none; float:left;" data-id="time_schedule_edit_div_${count}">X</a>
                                        </div>
                                </div>`;
                        });
                        scheduleInputs += `<button class="mt-2 add_more_time_schedule_edit">+ Add more</button>`;
                        editModal.find('.time_schedule_edit').append(scheduleInputs);
                } else {
                        let scheduleInputs = `<div class="row mt-2" id="time_schedule_edit_div_1">
                                        <div class="col-md-6">
                                                <input type="time" style="width:100%;" class="form-control" id="time_schedule_edit_1" name="time_schedules_edit[]" value="">
                                        </div>
                                        <div class="col-md-6"></div>
                                </div>`;
                        scheduleInputs += `<button class="mt-2 add_more_time_schedule_edit">+ Add more</button>`;
                        editModal.find('.time_schedule_edit').empty();
                        editModal.find('.time_schedule_edit').append(scheduleInputs);
                        editModal.find('input[name="is_edit_time_schedule_required"]').prop('checked', false);
                }
                // Store the membership ID for the update request
                editModal.find('input[name="membership_id"]').val(id);
        });

        // Handle edit membership form submission
        $('.edit_membership').click(async function(e) {
                e.preventDefault();
                const form = $(this).closest('form');
                $('.loadercontent').html('Validating the data, please wait...');
                $("#loader").show();
                let validation_result = await validate_membership_data(form);
                if(!validation_result[1]) {
                        $('.loadercontent').html('');
                        $("#loader").hide();
                        toastr.options.timeOut = 5000;
                        toastr.error(validation_result[0] || "Validation failed! Please check all the data in the form");
                } else {
                        $('.loadercontent').html('Updating membership plan, please wait...');
                        $("#loader").show();
                        console.log('Form data to be submitted:', form.serialize());
                        $.ajax({
                                url: baseUrl + '/api/edit_memberships',
                                method: 'POST',
                                data: form.serialize(),
                                success: function(response) {
                                        $('.loadercontent').html('');
                                        $("#loader").hide();
                                        toastr.options.timeOut = 5000;
                                        toastr.success(response.message || "Membership plan updated successfully");
                                        location.reload();
                                },
                                error: function(xhr, status, err) {
                                        $('.loadercontent').html('');
                                        $("#loader").hide();
                                        toastr.options.timeOut = 5000;
                                        toastr.error("Error updating membership plan");
                                }
                        });
                }
        });

        async function validate_membership_data(form) {
                console.log('Validating membership data...');
                console.log(form.serializeArray());
                if(form.find('#is_edit_time_schedule_required').is(':checked')) {
                        let time_schedules_edit = form.find('input[name="time_schedules_edit[]"]').map(function() {
                                return $(this).val();
                        }).get().filter(val => val); // Get values and filter out empty ones
                        if(time_schedules_edit.length == 0) {
                                return ['Atleast one time schedule is required', false];
                        }
                }
                return ['Validation passed', true];
        }

        // Show/hide time schedule inputs based on checkbox
        $('#is_time_schedule_required').change(function() {
                if($(this).is(':checked')) {
                        $('.time_schedule').removeClass('d-none');
                } else {
                        $('.time_schedule').addClass('d-none');
                }
        });

        // Add more schedule time input
        $(document).on('click', '.add_more_time_schedule', function(e) {
                e.preventDefault();
                let count = $('.time_schedule input[type="time"]').length;
                count++;
                const newInput = `<div class="row mt-2" id="time_schedule_div_${count}">
                        <div class="col-md-6">
                                <input type="time" class="form-control input_schedule_time" id="time_schedule_${count}" name="time_schedules[]">
                        </div>
                        <div class="col-md-6">
                                <a class="mt-1 remove_time_schedule" style="text-decoration:none; float:left;" data-id="time_schedule_div_${count}">X</a>
                        </div>
                </div>`;
                $(`.time_schedule #time_schedule_div_${count - 1}`).after(newInput);
        });

        // Rmove schedule time input
        $(document).on('click', '.remove_time_schedule', function(e) {
                e.preventDefault();
                const divId = $(this).data('id');
                $(`#${divId}`).remove();
        });

        // Show/hide time schedule inputs based on checkbox
        $('#is_edit_time_schedule_required').change(function() {
                if($(this).is(':checked')) {
                        $('.time_schedule_edit').removeClass('d-none');
                } else {
                        $('.time_schedule_edit').addClass('d-none');
                }
        });

        // Add more schedule time input
        $(document).on('click', '.add_more_time_schedule_edit', function(e) {
                e.preventDefault();
                let count = $('.time_schedule_edit input[type="time"]').length;
                count++;
                const newInput = `<div class="row mt-2" id="time_schedule_edit_div_${count}">
                        <div class="col-md-6">
                                <input type="time" style="width:100%;" class="form-control" id="time_schedule_edit_${count}" name="time_schedules_edit[]">
                        </div>
                        <div class="col-md-6">
                                <a class="mt-1 remove_time_schedule_edit" style="text-decoration:none; float:left;" data-id="time_schedule_edit_div_${count}">X</a>
                        </div>
                </div>`;
                $(`.time_schedule_edit #time_schedule_edit_div_${count - 1}`).after(newInput);
        });

        // Rmove schedule time input
        $(document).on('click', '.remove_time_schedule_edit', function(e) {
                e.preventDefault();
                const divId = $(this).data('id');
                $(`#${divId}`).remove();
        });

        // Clear selected data in membership add form when modal is hidden
        $('#addMembershipModal').on('hidden.bs.modal', function() {
                $(this).find('form')[0].reset();
                $('.time_schedule').addClass('d-none');
        });

        // Clear selected data in membership edit form when modal is hidden
        $('#editMembershipModal').on('hidden.bs.modal', function() {
                $(this).find('form')[0].reset();
                $('.time_schedule_edit').addClass('d-none');
        });

        $('#memberSearch').on('input', function() {
                const query = $(this).val().toLowerCase();
                const matchedMembers = allMembers.filter(member => member.name.toLowerCase().includes(query));
                const container = $('.matched_members');
                container.empty();
                if (query.length === 0) {
                        return;
                }
                if (matchedMembers.length === 0) {
                        container.append('<p>No members found.</p>');
                        return;
                }
                matchedMembers.forEach(member => {
                        const memberDiv = `<div style="padding:8px; border-bottom:1px solid #ddd;"><a href="${member.id}" style="text-decoration:none;">${member.name}</a></div>`;
                        container.append(memberDiv);
                });
        });

        // Handle edit button click to populate edit modal
        $(document).on('click', '[data-bs-target="#addHealthRecord"]', function() {
                const button = $(this);
                const id = button.data('id');
                const name = button.data('name');
                
                // Populate the edit modal form fields
                const editModal = $('#addHealthRecord');
                editModal.find('input[name="membership_id"]').val(id);
                editModal.find('.member_name').text(name);
        });

        $('.add_health_record').click(function(e) {
                e.preventDefault();
                $('.loadercontent').html('Adding health record, please wait...');
                $("#loader").show();
                const form = $(this).closest('form');
                $.ajax({
                        url: baseUrl + '/api/add_health_record',
                        method: 'POST',
                        data: form.serialize(),
                        success: function(response) {
                                $('.loadercontent').html('');
                                $("#loader").hide();
                                form[0].reset();
                                toastr.options.timeOut = 5000;
                                toastr.success(response.message || "Health record added");
                                location.reload();
                        },
                        error: function(xhr, status, err) {
                                $('.loadercontent').html('');
                                $("#loader").hide();
                                toastr.options.timeOut = 5000;
                                toastr.error("Error adding health record");
                        }
                });
        });

        $('#memberSearchFeeCollection').on('input', function() {
                const query = $(this).val().toLowerCase();
                const matchedMembers = total_members.filter(member => member.name.toLowerCase().includes(query) || 
                                       member.id.toString().includes(query) || 
                                       member.phone_no.includes(query));
                const container = $('.matched_members');
                container.empty();
                if (query.length === 0) {
                        return;
                }
                if (matchedMembers.length === 0) {
                        container.append('<p>No members found.</p>');
                        return;
                }
                matchedMembers.forEach(member => {
                        const memberDiv = `<div style="padding:8px; border-bottom:1px solid #ddd; cursor:pointer;"><a class="member_link" data-id="${member.id}" data-name="${member.name}" style="text-decoration:none;">${member.name}</a></div>`;
                        container.append(memberDiv);
                });
        });

        $(document).on('click', '.member_link', function() {
                const memberId = $(this).data('id');
                $.ajax({
                        url: baseUrl + '/api/get_payment_schedule/' + memberId,
                        method: 'GET',
                        success: function(response) {
                                $('.loadercontent').html('');
                                $("#loader").hide();
                                if(response && response.status === 'success' && response.data) {
                                        $('.matched_members').empty();
                                        $('.pay_for_multiple_members').addClass('d-none');
                                        $('#instructions_list_fee_collection_1').text('Select the checkbox for the month for which fees to be paid.');
                                        $('#memberSearchFeeCollection').val('');
                                        const scheduleContainer = $('.fee_schedule');
                                        scheduleContainer.empty();
                                        let scheduleHtml = `<h5 class="attn_report_heading">Payment Schedule for <b>${response.data.membername}</b></h5><h6 style="color:red;">Check the boxes to pay fee</h6>`;
                                        scheduleHtml += `<table style="width:100%; border-collapse:collapse; margin-top:12px;">
                                                <thead>
                                                        <tr>
                                                                <th class="fee_collection_filtered_table_thead_data">Month</th>
                                                                <th class="fee_collection_filtered_table_thead_data">Due Date</th>
                                                                <th class="fee_collection_filtered_table_thead_data">Amount</th>
                                                                <th class="fee_collection_filtered_table_thead_data" style="text-align:center;">Status</th>
                                                        </tr>
                                                </thead>
                                                <tbody>`;
                                        response.data.payment_schedule.forEach(item => {
                                                //const dueDate = new Date(item.due_date);
                                                let color = item.is_paid ? 'green' : 'red';
                                                scheduleHtml += `<tr>
                                                        <td class="fee_collection_filtered_table_tbody_data">${item.for_month}</td>
                                                        <td class="fee_collection_filtered_table_tbody_data">${item.due_date}</td>
                                                        <td class="fee_collection_filtered_table_tbody_data">${item.amount}</td>`;
                                                        if(response.data.membership.payment_type === 'single') {
                                                                scheduleHtml += `<td class="fee_collection_filtered_table_tbody_data" style="text-align:center; color:${color};">
                                                                ${item.is_paid ? `Paid </a>` : 'N/A'}</td>`;
                                                        } else {
                                                                scheduleHtml += `<td class="fee_collection_filtered_table_tbody_data" style="text-align:center; color:${color};">
                                                                ${item.is_paid ? `Paid &nbsp;&nbsp;&nbsp;<a href="${baseUrl}/monthly/receipt/download/${item.id}/${response.data.membership.id}" target="_blank" style="text-decoration:none; color:blue; cursor:pointer;">📄 Download Receipt</a>` : '<input type="checkbox" class="check_for_pay" name="payment[]" value="' + item.id + '">'}
                                                                </td>`;
                                                        }
                                                scheduleHtml += `</tr>`;
                                        });
                                        scheduleHtml += `</tbody></table>`;
                                        if(response.data.membership.payment_type === 'single') {
                                                scheduleHtml += `<a href="${baseUrl}/onetime/receipt/download/${memberId}/${response.data.membership.id}" 
                                                target="_blank" style="text-decoration:none; color:blue; cursor:pointer;">
                                                <button class="btn btn-success mt-3 process_payment" style="margin-bottom: 10px;">📄 Download Receipt
                                                </button></a>`;
                                        } else {
                                                scheduleHtml += `<a href="${baseUrl}/fee_collection"><button class="mt-3 back_to_fee_collection">BACK</button></a>
                                                <button class="mt-3 process_payment" data-bs-toggle="modal" data-bs-target="#feePaymentModal">Process Payment</button>`;
                                        }
                                        scheduleContainer.append(scheduleHtml);
                                } else {
                                        toastr.options.timeOut = 5000;
                                        toastr.error("No payment schedule found for this member");
                                }
                        },
                        error: function(xhr, status, err) {
                                $('.loadercontent').html('');
                                $("#loader").hide();
                                toastr.options.timeOut = 5000;
                                toastr.error("Error adding health record");
                        }
                });
        });

        $(document).on('click', '.check_for_pay', function() {
                $('.check_for_pay:checked').each(function() {
                        selectedPayments.push($(this).val());
                });
        });

        $(document).on('click', '.check_pay_for_multiple_member', function() {
                $('.check_pay_for_multiple_member:checked').each(function() {
                        selectedMembersPayments.push($(this).val());
                });
        });

        $(document).on('click', '[data-bs-target="#feePaymentModal"]', function() {
                const feePaymentModal = $('#feePaymentModal');
                feePaymentModal.find('input[name="payment_ids"]').val(selectedPayments);
        });

        $(document).on('click', '[data-bs-target="#multipleFeePaymentModal"]', function() {
                const feePaymentModal = $('#multipleFeePaymentModal');
                //feePaymentModal.find('input[name="payment_ids"]').val(selectedMembersPayments);
                selectedMembersPayments = Array.from(new Set(selectedMembersPayments)); // Remove duplicates
                let htmlContent = '';
                if(selectedMembersPayments.length === 0) {
                        $('.process_multiple_payment').addClass('d-none');
                        htmlContent = '<p style="color:red;">No members selected for payment. Please select at least one member.</p>';
                } else {
                        selectedMembersPayments.forEach(id => {
                                htmlContent += `Payment details for member ID: <b>${id}</b><hr/>`;
                                htmlContent += `<input type="hidden" name="payment_member_ids[]" value="${id}">`;
                                htmlContent += `<div class="row">
                                <div class="col-md-6 mb-3">
                                        <label for="feeType" class="form-label">Fee Types</label>
                                        <select class="form-select" id="feeType" name="type[]" required>
                                        <option value="monthly">Monthly</option>
                                        </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                        <label for="paymentMethod" class="form-label">Payment Method</label>
                                        <select class="form-select" id="paymentMethod" name="payment_method[]" required>
                                        <option value="">Select Payment Method</option>
                                        <option value="cash">Cash</option>
                                        <option value="upi">UPI</option>
                                        </select>
                                </div>
                                </div>
                                <div class="row">
                                <div class="col-md-12 mb-3">
                                        <label for="transactionId" class="form-label">UPI Transaction ID</label>
                                        <input type="text" class="form-control" id="transactionId" name="transaction_id[]" required>
                                </div>
                                </div>
                                <div class="row">
                                <div class="col-md-12 mb-3">
                                        <label for="note" class="form-label">Note</label>
                                        <textarea class="form-control" id="note" name="note[]" rows="3" placeholder="Additional notes about the payment"></textarea>
                                </div>
                                </div>`;
                        });
                        $('.process_multiple_payment').removeClass('d-none');
                }
                feePaymentModal.find('.members_payment_info').html(htmlContent);
        });

        // Clear selectedPayments array when feePaymentModal is closed
        $('#feePaymentModal').on('hidden.bs.modal', function() {
                selectedPayments = [];
        });

        $('#multipleFeePaymentModal').on('hidden.bs.modal', function() {
                selectedMembersPayments = [];
        });

        //Process fee payment
        $(document).on('click', '.proceed_payment', function(e) {
                e.preventDefault();
                if(selectedPayments.length === 0) {
                        toastr.options.timeOut = 5000;
                        toastr.error("Select atleast one payment to process");
                        exit;
                } else {
                        $('.loadercontent').html('Processing payment, please wait...');
                        $("#loader").show();
                        const form = $(this).closest('form');
                        $.ajax({
                                url: baseUrl + '/api/process_fee_payment',
                                method: 'POST',
                                data: form.serialize(),
                                success: function(response) {
                                        if(response.status && response.status === 'failed') {
                                                $('.loadercontent').html('');
                                                $("#loader").hide();
                                                toastr.options.timeOut = 5000;
                                                toastr.error(response.message || "Error processing payment");
                                                return;
                                        } else if(response.status && response.status === 'success') {
                                                $('.loadercontent').html('');
                                                $("#loader").hide();
                                                form[0].reset();
                                                selectedPayments = [];
                                                toastr.options.timeOut = 5000;
                                                toastr.success(response.message || "Payment processed successfully");
                                                location.reload();
                                        }
                                },
                                error: function(xhr, status, err) {
                                        $('.loadercontent').html('');
                                        $("#loader").hide();
                                        toastr.options.timeOut = 5000;
                                        toastr.error("Error processing payment");
                                }
                        });       
                }
        });

        //Process fee payment for multiple users
        $(document).on('click', '.proceed_payment_multiple', function(e) {
                e.preventDefault();
                if(selectedMembersPayments.length === 0) {
                        toastr.options.timeOut = 5000;
                        toastr.error("Select atleast one user to process");
                        exit;
                } else {
                        $('.loadercontent').html('Processing payment, please wait...');
                        $("#loader").show();
                        const form = $(this).closest('form');
                        $.ajax({
                                url: baseUrl + '/api/process_multiple_fee_payment',
                                method: 'POST',
                                data: form.serialize(),
                                success: function(response) {
                                        if(response.status && response.status === 'failed') {
                                                $('.loadercontent').html('');
                                                $("#loader").hide();
                                                toastr.options.timeOut = 5000;
                                                toastr.error(response.message || "Error processing payment");
                                                return;
                                        } else if(response.status && response.status === 'success') {
                                                $('.loadercontent').html('');
                                                $("#loader").hide();
                                                form[0].reset();
                                                selectedMembersPayments = [];
                                                $('.check_pay_for_multiple_member:checked').each(function() {
                                                        $(this).prop('checked', false);
                                                });
                                                toastr.options.timeOut = 5000;
                                                toastr.success(response.message || "Payment processed successfully");
                                                location.reload();
                                        }
                                },
                                error: function(xhr, status, err) {
                                        $('.loadercontent').html('');
                                        $("#loader").hide();
                                        toastr.options.timeOut = 5000;
                                        toastr.error("Error processing payment");
                                }
                        });       
                }
        });

        //Save config settings
        $('.save_config').click(function(e) {
                e.preventDefault();
                $('.loadercontent').html('Saving configuration, please wait...');
                $("#loader").show();
                const registrationFee = $('#registrationFee').val();
                const monthlyFee = $('#monthlyFee').val();
                $.ajax({
                        url: baseUrl + '/api/save_config',
                        method: 'POST',
                        data: {
                                registration_fee: registrationFee,
                                monthly_fee: monthlyFee
                        },
                        success: function(response) {
                                $('.loadercontent').html('');
                                $("#loader").hide();
                                toastr.options.timeOut = 5000;
                                toastr.success(response.message || "Configuration saved successfully");
                        },
                        error: function(xhr, status, err) {
                                $('.loadercontent').html('');
                                $("#loader").hide();
                                toastr.options.timeOut = 5000;
                                toastr.error("Error saving configuration");
                        }
                });
        });

        $('#memberSearchforkealthrec').on('input', function() {
                const query = $(this).val().toLowerCase();
                const matchedMembers = total_members_health.filter(member => member.name.toLowerCase().includes(query));
                const container = $('.matched_members_health_rec');
                const tableContainer = $('#health_rec_basic_table');
                container.empty();
                $('#health_rec_basic_table_body').remove();
                if (query.length === 0) {
                        let tbody = '<tbody id="health_rec_basic_table_body">';
                        total_members_health.forEach(member => {
                                tbody += `<tr style="background-color:#ffffff;">
                                <td style="padding:12px; border:1px solid #ddd;">${member.id}</td>
                                <td style="padding:12px; border:1px solid #ddd;"><a href="#" style="text-decoration:none;" 
                                data-bs-toggle="modal" data-bs-target="#progressChartDisplayModal" data-member_id="${member.id}" data-member_name="${member.name}">${member.name}</a></td>
                                <td style="padding:12px; border:1px solid #ddd;">${member.phone_no}</td>
                                <td style="padding:12px; border:1px solid #ddd;">
                                        <button class="add_health_record_btn"
                                        data-id="${member.id}"
                                        data-name="${member.name}"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#addHealthRecord"
                                        >
                                        Add Record</button>
                                </td>
                                </tr>`;
                        });
                        tbody += '</tbody>';
                        tableContainer.append(tbody);
                        return;
                }
                if (matchedMembers.length === 0) {
                        container.append('<p>No members found.</p>');
                        return;
                }
                let tbody = '<tbody id="health_rec_basic_table_body">';
                matchedMembers.forEach(member => {
                        tbody += `<tr style="background-color:#ffffff;">
                            <td style="padding:12px; border:1px solid #ddd;">${member.id}</td>
                            <td style="padding:12px; border:1px solid #ddd;"><a href="#" style="text-decoration:none;" 
                                data-bs-toggle="modal" data-bs-target="#progressChartDisplayModal" data-member_id="${member.id}" data-member_name="${member.name}">${member.name}</a></td>
                            <td style="padding:12px; border:1px solid #ddd;">${member.phone_no}</td>
                            <td style="padding:12px; border:1px solid #ddd;">
                                <button class="add_health_record_btn"
                                    data-id="${member.id}"
                                    data-name="${member.name}"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#addHealthRecord"
                                >
                                Add Record</button>
                            </td>
                        </tr>`;
                });
                tbody += '</tbody>';
                tableContainer.append(tbody);
        });

        $(document).on('click', '.mark_attendance', function(e) {
                e.preventDefault();
                const memberId = $(this).data('id');
                $.ajax({
                        url: baseUrl + '/api/save_attendance'+'/'+memberId,
                        method: 'POST',
                        success: function(response) {
                                if(response.status && response.status === 'success') {
                                        $('#attendance_action_'+memberId).html('');
                                        toastr.options.timeOut = 5000;
                                        toastr.success(response.message || "Configuration saved successfully");
                                        $('#attendance_action_'+memberId).html('<span class="check_in_display_div">Checked In at '+response.check_in_time+' ('+response.shift.charAt(0).toUpperCase() + response.shift.slice(1)+')</span>');
                                        // Update total_members_attn variable to reflect the change
                                        const memberIndex = total_members_attn.findIndex(m => m.id === memberId);
                                        if (memberIndex !== -1) {
                                                total_members_attn[memberIndex].status = 'present';
                                                total_members_attn[memberIndex].check_in_time = response.check_in_time; //new Date(response.check_in_time).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                                                total_members_attn[memberIndex].shift = response.shift;
                                        }
                                } else if(response.status && response.status === 'failed') {
                                        toastr.options.timeOut = 5000;
                                        toastr.error(response.message || "Error saving attendance");
                                }
                        },
                        error: function(xhr, status, err) {
                                $('.loadercontent').html('');
                                $("#loader").hide();
                                toastr.options.timeOut = 5000;
                                toastr.error("Error saving configuration");
                        }
                });
        });

        $('#memberSearchforAttendance').on('input', function() {
                const query = $(this).val().toLowerCase();
                const matchedMembers = total_members_attn.filter(member => member.name.toLowerCase().includes(query));
                const container = $('.matched_members_attendance');
                const tableContainer = $('#attn_basic_table');
                container.empty();
                $('#attn_basic_table_body').remove();
                if (query.length === 0) {
                        let tbody = '<tbody id="attn_basic_table_body">';
                        total_members_attn.forEach(member => {
                                tbody += `<tr style="background-color:#ffffff;">
                                        <td class="attendance_tbody_data">${member.id}</td>
                                        <td class="attendance_tbody_data"><a href="#" style="text-decoration:none;" 
                                                data-bs-toggle="modal" data-bs-target="#attendanceReportModal" data-member_id="${member.id}" data-member_name="${member.name}">${member.name}</a></td>
                                        <td class="attendance_tbody_data">${member.phone_no}</td>
                                        <td class="attendance_tbody_data" id="attendance_action_${member.id}">`;
                                        if(member.status == 'present') {
                                                tbody += `<span class="check_in_display_div">Checked In at ${member.check_in_time} (${member.shift.charAt(0).toUpperCase() + member.shift.slice(1)})</span>`;
                                        } else {
                                                tbody += `<button class="mark_attendance" data-id="${member.id}">Check In</button>`;
                                        }
                                        tbody += `</td>`;
                                tbody += `</tr>`;
                        });
                        tbody += '</tbody>';
                        tableContainer.append(tbody);
                        return;
                }
                if (matchedMembers.length === 0) {
                        container.append('<p>No members found.</p>');
                        return;
                }
                let tbody = '<tbody id="attn_basic_table_body">';
                matchedMembers.forEach(member => {
                        tbody += `<tr style="background-color:#ffffff;">
                                <td class="attendance_tbody_data">${member.id}</td>
                                <td class="attendance_tbody_data"><a href="#" style="text-decoration:none;" 
                                                data-bs-toggle="modal" data-bs-target="#attendanceReportModal" data-member_id="${member.id}" data-member_name="${member.name}">${member.name}</a></td>
                                <td class="attendance_tbody_data">${member.phone_no}</td>
                                <td class="attendance_tbody_data" id="attendance_action_${member.id}">`;
                                if(member.status == 'present') {
                                        tbody += `<span class="check_in_display_div">Checked In at ${member.check_in_time} (${member.shift.charAt(0).toUpperCase() + member.shift.slice(1)})</span>`;
                                } else {
                                        tbody += `<button class="mark_attendance" data-id="${member.id}">Check In</button>`;
                                }
                                tbody += `</td>`;
                        tbody += `</tr>`;
                });
                tbody += '</tbody>';
                tableContainer.append(tbody);
        });

        $('#memberSearchforAttendanceReport').on('input', function() {
                $('#all_members').val('');
                $('.attendance_report').empty();
                const query = $(this).val().toLowerCase();
                const matchedMembers = total_members_attn_report.filter(member => member.name.toLowerCase().includes(query));
                const container = $('.matched_members_attendance_report');
                container.empty();
                if (query.length === 0) {
                        return;
                }
                if (matchedMembers.length === 0) {
                        container.append('<p>No members found.</p>');
                        return;
                }
                matchedMembers.forEach(member => {
                        const memberDiv = `<div style="padding:8px; border-bottom:1px solid #ddd; cursor:pointer;"><a class="member_name" data-id="${member.id}" data-name="${member.name}" style="text-decoration:none;">${member.name}</a></div>`;
                        container.append(memberDiv);
                });
        });

        $('.matched_members_attendance_report').on('click', '.member_name', function() {
                const memberId = $(this).data('id');
                const memberName = $(this).data('name');
                $('#all_members').val(memberId);
                $('#memberSearchforAttendanceReport').val(memberName);
                $('.matched_members_attendance_report').empty();
        });

        $('#report_btn').on('click', function(){
                const memberId = $('#all_members').val();
                const from_date = $('#from_date').val();
                const to_date = $('#to_date').val();
                $.ajax({
                        url: baseUrl + '/api/attendance_report',
                        method: 'GET',
                        data: {from_date, to_date, memberId},
                        success: function(response) {
                                console.log('Attendance report response:', response);
                                if(response && response.status === 'success' && response.data) {
                                        let data = response.data;
                                        let members = response.members;
                                        const reportContainer = $('.attendance_report');
                                        reportContainer.empty();
                                        if(memberId.toLowerCase() == '' || memberId.toLowerCase() == 'all') {
                                                let reportHtml = `<h5 class="attn_report_heading">Attendance Report for All members in the period ${from_date} to ${to_date}</h5>`;
                                                reportHtml += `<div class="card hide-scrollbar" style="background:#fff;border:1px solid #ddd;border-radius:8px;padding:8px; margin-bottom:10px;">`;
                                                // Process the attendance data structure from backend
                                                // Backend returns: {memberId: {date: {id, name, status}}}
                                                members.forEach(member => {
                                                        reportHtml += `<p style="margin-top:10px; font-weight:bold;">ID : ${member.id} Name : ${member.name}</p>`;
                                                        Object.keys(data).forEach(memberId => {
                                                                if(memberId != member.id) return;
                                                                const memberData = data[memberId];
                                                                let p = 0;
                                                                let a = 0;
                                                                let na = 0;
                                                                reportHtml += `<table style="width:100%; border-collapse:collapse; margin-top:12px;">
                                                                                <thead>
                                                                                        <tr>
                                                                                                <th style="border:1px solid #ddd; padding:8px;">Date</th>
                                                                                                <th style="border:1px solid #ddd; padding:8px;">Status</th>
                                                                                        </tr>
                                                                                </thead>
                                                                                <tbody>`;
                                                                Object.keys(memberData).forEach(date => {
                                                                        const attendanceRecord = memberData[date];
                                                                        const color = attendanceRecord.status.toLowerCase() === 'present' ? 'green' : attendanceRecord.status.toLowerCase() === 'absent' ? 'red' : 'orange';
                                                                        if (attendanceRecord.status.toLowerCase() === 'present') {
                                                                                p++;
                                                                        } else if (attendanceRecord.status.toLowerCase() === 'absent') {
                                                                                a++;
                                                                        } else {
                                                                                na++;
                                                                        }
                                                                        const formatted = new Intl.DateTimeFormat('en-IN', {
                                                                                day: '2-digit',
                                                                                month: 'short',
                                                                                year: 'numeric'
                                                                        }).format(new Date(date));
                                                                        reportHtml += `<tr>
                                                                                <td style="border:1px solid #ddd; padding:8px;">${formatted}</td>
                                                                                <td style="border:1px solid #ddd; padding:8px; color:${color}; font-weight:bold;">${attendanceRecord.status == 'NA' ? 'Scheduled' : attendanceRecord.status}</td>
                                                                        </tr>`;
                                                                });
                                                                reportHtml += `<tr>
                                                                                <td style="border:1px solid #ddd; padding:8px;" colspan="2">
                                                                                        <p style="float:right; margin-right:150px; margin-top:20px;">
                                                                                        <b>Present : <span style="color:green;">${p}</span>, 
                                                                                        Absent : <span style="color:red;">${a}</span>, 
                                                                                        Scheduled : <span style="color:orange;">${na}</span></b></p>
                                                                                </td>
                                                                        </tr>`;
                                                                reportHtml += `</tbody></table>`;
                                                        });
                                                });
                                                reportHtml += `</div>`;
                                                
                                                reportContainer.append(reportHtml);        
                                        } else {
                                                let reportHtml = `<h5 class="attn_report_heading">Attendance Report of ${response.member.name}</h5>`;
                                                reportHtml += `<div class="card hide-scrollbar" style="background:#fff;border:1px solid #ddd;border-radius:8px;padding:8px; margin-bottom:10px;">`;
                                                reportHtml += `<table style="width:100%; border-collapse:collapse; margin-top:12px;">
                                                        <thead>
                                                                <tr>
                                                                        <th style="border:1px solid #ddd; padding:8px;">Date</th>
                                                                        <th style="border:1px solid #ddd; padding:8px;">Check-In Time</th>
                                                                        <th style="border:1px solid #ddd; padding:8px;">Shift</th>
                                                                </tr>
                                                        </thead>
                                                        <tbody>`;
                                                let p = 0;
                                                let a = 0;
                                                let na = 0;
                                                Object.keys(data).forEach(memberId => {
                                                        const item = data[memberId];
                                                        let color = '';
                                                        if (item.status && item.status.toLowerCase() === 'present') {
                                                                p++;
                                                                color = 'green';
                                                        } else if (item.status && item.status.toLowerCase() === 'absent'){
                                                                a++;
                                                                color = 'red';
                                                        } else {
                                                                na++;
                                                                color = 'orange';
                                                        }
                                                        const formatted = new Intl.DateTimeFormat('en-IN', {
                                                                                day: '2-digit',
                                                                                month: 'short',
                                                                                year: 'numeric'
                                                                        }).format(new Date(item.date));
                                                        reportHtml += `<tr>
                                                                <td style="border:1px solid #ddd; padding:8px;">${formatted}</td>
                                                                <td style="border:1px solid #ddd; padding:8px; color:${color};">${item.status == 'NA' ? 'Scheduled' : item.check_in_time}</td>
                                                                <td style="border:1px solid #ddd; padding:8px; color:${color};">${item.status == 'NA' ? 'Scheduled' : item.shift.charAt(0).toUpperCase() + item.shift.slice(1)}</td>
                                                        </tr>`;
                                                });
                                                reportHtml += `</tbody></table>`;
                                                reportHtml += `<p style="float:right; margin-right:150px; margin-top:20px;"><b>Present : <span style="color:green;">${p}</span><br /> Absent : <span style="color:red;">${a}</span><br /> Scheduled : <span style="color:orange;">${na}</span></b></p>`;
                                                reportHtml += `</div>`;
                                                reportContainer.append(reportHtml);
                                        }
                                } else {
                                        toastr.options.timeOut = 5000;
                                        toastr.error(response.message || "No attendance records found for this member");
                                }
                        },
                        error: function(xhr, status, err) {
                                toastr.options.timeOut = 5000;
                                toastr.error("Error fetching attendance report");
                        }
                });
        });

        // Handle download attendance report button click
        $('.download_attn_report').on('click', function(e) {
                e.preventDefault();
                const memberId = $('#all_members').val();
                const from_date = $('#from_date').val();
                const to_date = $('#to_date').val();
                
                if (!from_date || !to_date) {
                        toastr.options.timeOut = 5000;
                        toastr.error("Please select from date and to date first");
                        return;
                }
                
                // Build download URL with parameters
                const downloadUrl = baseUrl + '/api/attendance_report/download?' + 
                        'from_date=' + encodeURIComponent(from_date) + 
                        '&to_date=' + encodeURIComponent(to_date) + 
                        '&memberId=' + encodeURIComponent(memberId || '');
                
                // Trigger download
                window.open(downloadUrl, '_blank');
        });

        //Trainer registration
        $('.register_trainer').click(function(e) {
                e.preventDefault();
                $('.loadercontent').html('Registering trainer, please wait...');
                $("#loader").show();
                const form = $(this).closest('form');
                $.ajax({
                        url: baseUrl + '/api/register_trainer',
                        method: 'POST',
                        data: form.serialize(),
                        success: function(response) {
                                $('.loadercontent').html('');
                                $("#loader").hide();
                                toastr.options.timeOut = 5000;
                                toastr.success(response.message || "Member registered successfully");
                                form[0].reset();
                        },
                        error: function(xhr, status, err) {
                                toastr.options.timeOut = 5000;
                                toastr.error("Error registering member");
                                //console.error('Error registering member:', status, err, xhr.responseText);
                        }
                });
        });

        //Allot trainer to member
        $(document).on('click', '.allot_trainer', function(e) {
                e.preventDefault();
                const memberId = $('#member_id').val();
                const trainerIds = $('input[name="trainers[]"]:checked').map(function() {
                        return this.value;
                }).get();
                //const trainerId = trainerIds.length > 0 ? trainerIds : null; // Assuming single selection for now
                if(!memberId) {
                        toastr.options.timeOut = 5000;
                        toastr.error("Select a member to allot trainer");
                        return;
                }
                if(!trainerIds || trainerIds.length === 0) {
                        toastr.options.timeOut = 5000;
                        toastr.error("Select atleast one trainer to allot");
                        return;
                }
                $('.loadercontent').html('Alloting trainer, please wait...');
                $("#loader").show();
                $.ajax({
                        url: baseUrl + '/api/allot_trainer',
                        method: 'POST',
                        data: {member_id: memberId, trainer_ids: trainerIds},
                        success: function(response) {
                                $('.loadercontent').html('');
                                $("#loader").hide();
                                toastr.options.timeOut = 5000;
                                toastr.success(response.message || "Trainer alloted successfully");
                        },
                        error: function(xhr, status, err) {
                                toastr.options.timeOut = 5000;
                                toastr.error("Error alloting trainer");
                        }
                });
        });

        // Check trainer checkboxes based on member selection
        $('#member_id').change(function() {
                const memberId = $(this).val();
                if(!memberId) {
                        $('input[name="trainers[]"]').prop('checked', false);
                        return;
                }
                $.ajax({
                        url: baseUrl + '/api/get_trainers_for_member',
                        method: 'GET',
                        data: {member_id: memberId},
                        success: function(response) {
                                console.log('Get trainers for member response:', response);
                                if(response.status && response.status === 'success' && response.data) {
                                        $('input[name="trainers[]"]').prop('checked', false);
                                        $(response.data).each(function(index, trainerId) {
                                                $('#trainer-'+trainerId).prop('checked', true);
                                        });
                                } else {
                                        $('input[name="trainers[]"]').prop('checked', false);
                                }
                        },
                        error: function(xhr, status, err) {
                                toastr.options.timeOut = 5000;
                                toastr.error("Error alloting trainer");
                        }
                });
        });

        // Attendance report modal show
        $(document).on('click', '[data-bs-target="#attendanceReportModal"]', function() {
                const link = $(this);
                const member_id = link.data('member_id');
                const member_name = link.data('member_name');
                const currentDate = new Date();
                const current_month = currentDate.getMonth() + 1; // Months are zero-based
                const current_year = currentDate.getFullYear();
                const from_date = `${current_year}-${current_month}-01`;
                const last_date = new Date(current_year, current_month, 0).getDate(); // Last day of current month
                const to_date = `${current_year}-${current_month}-${last_date}`;
                $('.attendance_report').empty();
                const reportContent = $('#attendanceReportContent');
                reportContent.empty();
                reportContent.append('<p>Loading attendance report...</p>');
                $.ajax({
                        url: baseUrl + '/api/get_attendance_report'+'/'+member_id,
                        method: 'GET',
                        data: {from_date: from_date, to_date: to_date},
                        success: function(response) {
                                console.log('Attendance report modal response:', response);
                                if(response && response.status === 'success' && response.data) {
                                        let data = response.data;
                                        reportContent.empty();
                                        let reportHtml = ``;
                                        reportHtml += `<table style="width:100%; border-collapse:collapse; margin-top:12px;">
                                                <thead>
                                                        <tr>
                                                                <th class="individual_attendance_show">Date</th>
                                                                <th class="individual_attendance_show">Check-In</th>
                                                                <th class="individual_attendance_show">Shift</th>
                                                                <th class="individual_attendance_show">Status</th>
                                                        </tr>
                                                </thead>
                                                <tbody>`;
                                        let p = 0;
                                        let a = 0;
                                        let na = 0;
                                        Object.keys(data).forEach(memberId => {
                                                const item = data[memberId];
                                                if (item.status && item.status.toLowerCase() === 'present') {
                                                        p++;
                                                } else if (item.status && item.status.toLowerCase() === 'absent') {
                                                        a++;
                                                } else {
                                                        na++;
                                                }
                                                let color = item.status.toLowerCase() === 'absent' ? 'red' : item.status.toLowerCase() === 'present' ? 'green' : 'orange';
                                                const formatted = new Intl.DateTimeFormat('en-IN', {
                                                                day: '2-digit',
                                                                month: 'short',
                                                                year: 'numeric'
                                                        }).format(new Date(item.date));
                                                reportHtml += `<tr>
                                                        <td style="border:1px solid #ddd; padding:8px;">${formatted}</td>
                                                        <td style="border:1px solid #ddd; padding:8px;">${item.check_in_time}</td>
                                                        <td style="border:1px solid #ddd; padding:8px;">${item.shift}</td>
                                                        <td style="border:1px solid #ddd; padding:8px; color:${color};">${item.status === 'NA' ? 'Scheduled' : item.status}</td>
                                                </tr>`;
                                        });
                                        reportHtml += `</tbody></table>`;
                                        reportHtml += `<p style="float:right; margin-top:20px;"><b>Present : <span style="color:green;">${p}</span><br/>Absent : <span style="color:red;">${a}</span><br />Scheduled : <span style="color:orange;">${na}</span></b></p>`;
                                        reportContent.append(reportHtml);
                                } else {
                                        const reportContent = $('#attendanceReportContent');
                                        reportContent.empty();
                                        reportContent.append('<p>No attendance records found for this member.</p>');
                                }
                        },
                        error: function(xhr, status, err) {
                                const reportContent = $('#attendanceReportContent');
                                reportContent.empty();
                                reportContent.append('<p>Error fetching attendance report.</p>');
                        }
                });
                // Populate the attendance modal form fields
                const attnReportModal = $('#attendanceReportModal');
                attnReportModal.find('#memberName').text(member_name);
                attnReportModal.find('#attendanceMonth').text(all_months[current_month - 1] + ' ' + current_year);
        });

        $(document).on('click', '[data-bs-target="#progressChartDisplayModal"]', function() {
                let member_id = $(this).data('member_id');
                let member_name = $(this).data('member_name');
                const progressChartDisplayModal = $('#progressChartDisplayModal');
                progressChartDisplayModal.find('input[type="hidden"][name="member_id"]').val(member_id);
                progressChartDisplayModal.find('#memberName').text(member_name);
                progressChartDisplayModal.find('.progress_matric').prop('checked', false);
                progressChartDisplayModal.find('input[type="radio"][value="Weight"]').prop('checked', true);
                loadMemberProgressChart('Weight', member_id);
        });

        // Click progress_matric radio button
        $(document).on('click', '.progress_matric', function() {
                let matric = $(this).val();
                const progressChartDisplayModal = $('#progressChartDisplayModal');
                const member_id = progressChartDisplayModal.find('input[type="hidden"][name="member_id"]').val();
                loadMemberProgressChart(matric, member_id);
        });

        // Show Fee collection
        $(document).on('change', '#fee_month', function(e) {
                e.preventDefault();
                const fee_month = $(this).val();
                fee_collections_filter.fee_month = fee_month;
                fee_collections_filter.membership_id = $('#memberships').val();
                $.ajax({
                        url: baseUrl + '/api/fee_collections',
                        method: 'GET',
                        data: {fee_collections_filter},
                        success: function(response) {
                                let data = response;
                                displayFeeCollectionsChart(data, fee_collections_filter);
                        },
                        error: function(xhr, status, err) {
                                toastr.options.timeOut = 5000;
                                toastr.error("Error fetching fee collection report");
                        }
                });
        });

        $(document).on('change', '#memberships', function(e) {
                e.preventDefault();
                if($('#fee_month').val() == 0 || $('#fee_month').val() == '') {
                        toastr.options.timeOut = 5000;
                        toastr.error("Select a month");
                        return;
                }
                const membership = $(this).val();
                fee_collections_filter.membership_id = membership;
                fee_collections_filter.fee_month = $('#fee_month').val();
                $.ajax({
                        url: baseUrl + '/api/fee_collections',
                        method: 'GET',
                        data: {fee_collections_filter},
                        success: function(response) {
                                let data = response;
                                console.log('Fee collections response:', data);
                                displayFeeCollectionsChart(data, fee_collections_filter);
                        },
                        error: function(xhr, status, err) {
                                toastr.options.timeOut = 5000;
                                toastr.error("Error fetching fee collection report");
                        }
                });
        });
});
