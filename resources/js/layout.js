document.addEventListener('DOMContentLoaded', function() {
    const settingsButton = document.getElementById('settings-button');
    const settingsMenu = document.getElementById('settings-menu');
    const settingsArrow = document.getElementById('settings-arrow');

    const memberButton = document.getElementById('member-button');
    const memberMenu = document.getElementById('member-menu');
    const memberArrow = document.getElementById('member-arrow');

    const feeButton = document.getElementById('fee-button');
    const feeMenu = document.getElementById('fee-menu');
    const feeArrow = document.getElementById('fee-arrow');

    const attendanceButton = document.getElementById('attendance-button');
    const attendanceMenu = document.getElementById('attendance-menu');
    const attendanceArrow = document.getElementById('attendance-arrow');

    const trainerButton = document.getElementById('trainer-button');
    const trainerMenu = document.getElementById('trainer-menu');
    const trainerArrow = document.getElementById('trainer-arrow');

    if(settingsButton && settingsMenu) {
        settingsButton.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const isHidden = settingsMenu.classList.contains('hidden');
            if(isHidden) {
                memberMenu.classList.add('hidden');
                memberArrow.style.transform = 'rotate(0deg)';
                feeMenu.classList.add('hidden');
                feeArrow.style.transform = 'rotate(0deg)';
                attendanceMenu.classList.add('hidden');
                attendanceArrow.style.transform = 'rotate(0deg)';
                trainerMenu.classList.add('hidden');
                trainerArrow.style.transform = 'rotate(0deg)';
                settingsMenu.classList.remove('hidden');
                settingsArrow.style.transform = 'rotate(180deg)';
            } else {
                settingsMenu.classList.add('hidden');
                settingsArrow.style.transform = 'rotate(0deg)';
            }
        });
    }

    if(memberButton && memberMenu) {
        memberButton.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const isHidden = memberMenu.classList.contains('hidden');
            if(isHidden) {
                settingsMenu.classList.add('hidden');
                settingsArrow.style.transform = 'rotate(0deg)';
                feeMenu.classList.add('hidden');
                feeArrow.style.transform = 'rotate(0deg)';
                attendanceMenu.classList.add('hidden');
                attendanceArrow.style.transform = 'rotate(0deg)';
                trainerMenu.classList.add('hidden');
                trainerArrow.style.transform = 'rotate(0deg)';
                memberMenu.classList.remove('hidden');
                memberArrow.style.transform = 'rotate(180deg)';
            } else {
                memberMenu.classList.add('hidden');
                memberArrow.style.transform = 'rotate(0deg)';
            }
        });
    }

    if(feeButton && feeMenu) {
        feeButton.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const isHidden = feeMenu.classList.contains('hidden');
            if(isHidden) {
                settingsMenu.classList.add('hidden');
                memberMenu.classList.add('hidden');
                settingsArrow.style.transform = 'rotate(0deg)';
                memberArrow.style.transform = 'rotate(0deg)';
                attendanceMenu.classList.add('hidden');
                attendanceArrow.style.transform = 'rotate(0deg)';
                trainerMenu.classList.add('hidden');
                trainerArrow.style.transform = 'rotate(0deg)';
                feeMenu.classList.remove('hidden');
                feeArrow.style.transform = 'rotate(180deg)';
            } else {
                feeMenu.classList.add('hidden');
                feeArrow.style.transform = 'rotate(0deg)';
            }
        });
    }

    if(attendanceButton && attendanceMenu) {
        attendanceButton.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const isHidden = attendanceMenu.classList.contains('hidden');
            if(isHidden) {
                settingsMenu.classList.add('hidden');
                memberMenu.classList.add('hidden');
                feeMenu.classList.add('hidden');
                settingsArrow.style.transform = 'rotate(0deg)';
                memberArrow.style.transform = 'rotate(0deg)';
                feeArrow.style.transform = 'rotate(0deg)';
                trainerMenu.classList.add('hidden');
                trainerArrow.style.transform = 'rotate(0deg)';
                attendanceMenu.classList.remove('hidden');
                attendanceArrow.style.transform = 'rotate(180deg)';
            } else {
                attendanceMenu.classList.add('hidden');
                attendanceArrow.style.transform = 'rotate(0deg)';
            }
        });
    }

    if(trainerButton && trainerMenu) {
        trainerButton.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const isHidden = trainerMenu.classList.contains('hidden');
            if(isHidden) {
                settingsMenu.classList.add('hidden');
                memberMenu.classList.add('hidden');
                feeMenu.classList.add('hidden');
                settingsArrow.style.transform = 'rotate(0deg)';
                memberArrow.style.transform = 'rotate(0deg)';
                feeArrow.style.transform = 'rotate(0deg)';
                attendanceMenu.classList.add('hidden');
                attendanceArrow.style.transform = 'rotate(0deg)';
                trainerMenu.classList.remove('hidden');
                trainerArrow.style.transform = 'rotate(180deg)';
            } else {
                trainerMenu.classList.add('hidden');
                trainerArrow.style.transform = 'rotate(0deg)';
            }
        });
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!settingsButton.contains(e.target) && !settingsMenu.contains(e.target)) {
            settingsMenu.classList.add('hidden');
            settingsArrow.style.transform = 'rotate(0deg)';
        }
        if (!memberButton.contains(e.target) && !memberMenu.contains(e.target)) {
            memberMenu.classList.add('hidden');
            memberArrow.style.transform = 'rotate(0deg)';
        }
        if (!feeButton.contains(e.target) && !feeMenu.contains(e.target)) {
            feeMenu.classList.add('hidden');
            feeArrow.style.transform = 'rotate(0deg)';
        }
        if (!attendanceButton.contains(e.target) && !attendanceMenu.contains(e.target)) {
            attendanceMenu.classList.add('hidden');
            attendanceArrow.style.transform = 'rotate(0deg)';
        }
        if (!trainerButton.contains(e.target) && !trainerMenu.contains(e.target)) {
            trainerMenu.classList.add('hidden');
            trainerArrow.style.transform = 'rotate(0deg)';
        }
    });
    
    // Close dropdown when pressing Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            settingsMenu.classList.add('hidden');
            settingsArrow.style.transform = 'rotate(0deg)';
            memberMenu.classList.add('hidden');
            memberArrow.style.transform = 'rotate(0deg)';
            feeMenu.classList.add('hidden');
            feeArrow.style.transform = 'rotate(0deg)';
            attendanceMenu.classList.add('hidden');
            attendanceArrow.style.transform = 'rotate(0deg)';
            trainerMenu.classList.add('hidden');
            trainerArrow.style.transform = 'rotate(0deg)';
        }
    });
});