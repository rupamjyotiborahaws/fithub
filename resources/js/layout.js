document.addEventListener('DOMContentLoaded', function() {
    // Desktop dropdown elements
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

    // Mobile menu elements
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    const hamburgerIcon = document.getElementById('hamburger-icon');
    const closeIcon = document.getElementById('close-icon');

    // Mobile dropdown elements
    const mobileMemberButton = document.getElementById('mobile-member-button');
    const mobileMemberMenu = document.getElementById('mobile-member-menu');
    const mobileMemberArrow = document.getElementById('mobile-member-arrow');

    const mobileTrainerButton = document.getElementById('mobile-trainer-button');
    const mobileTrainerMenu = document.getElementById('mobile-trainer-menu');
    const mobileTrainerArrow = document.getElementById('mobile-trainer-arrow');

    const mobileFeeButton = document.getElementById('mobile-fee-button');
    const mobileFeeMenu = document.getElementById('mobile-fee-menu');
    const mobileFeeArrow = document.getElementById('mobile-fee-arrow');

    const mobileAttendanceButton = document.getElementById('mobile-attendance-button');
    const mobileAttendanceMenu = document.getElementById('mobile-attendance-menu');
    const mobileAttendanceArrow = document.getElementById('mobile-attendance-arrow');

    const mobileSettingsButton = document.getElementById('mobile-settings-button');
    const mobileSettingsMenu = document.getElementById('mobile-settings-menu');
    const mobileSettingsArrow = document.getElementById('mobile-settings-arrow');

    // Helper function to close all desktop dropdowns
    function closeAllDesktopDropdowns() {
        if (settingsMenu) {
            settingsMenu.classList.add('hidden');
            settingsArrow.style.transform = 'rotate(0deg)';
        }
        if (memberMenu) {
            memberMenu.classList.add('hidden');
            memberArrow.style.transform = 'rotate(0deg)';
        }
        if (feeMenu) {
            feeMenu.classList.add('hidden');
            feeArrow.style.transform = 'rotate(0deg)';
        }
        if (attendanceMenu) {
            attendanceMenu.classList.add('hidden');
            attendanceArrow.style.transform = 'rotate(0deg)';
        }
        if (trainerMenu) {
            trainerMenu.classList.add('hidden');
            trainerArrow.style.transform = 'rotate(0deg)';
        }
    }

    // Helper function to close all mobile dropdowns
    function closeAllMobileDropdowns() {
        if (mobileMemberMenu) {
            mobileMemberMenu.classList.add('hidden');
            mobileMemberArrow.style.transform = 'rotate(0deg)';
        }
        if (mobileTrainerMenu) {
            mobileTrainerMenu.classList.add('hidden');
            mobileTrainerArrow.style.transform = 'rotate(0deg)';
        }
        if (mobileFeeMenu) {
            mobileFeeMenu.classList.add('hidden');
            mobileFeeArrow.style.transform = 'rotate(0deg)';
        }
        if (mobileAttendanceMenu) {
            mobileAttendanceMenu.classList.add('hidden');
            mobileAttendanceArrow.style.transform = 'rotate(0deg)';
        }
        if (mobileSettingsMenu) {
            mobileSettingsMenu.classList.add('hidden');
            mobileSettingsArrow.style.transform = 'rotate(0deg)';
        }
    }

    // Mobile menu toggle
    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const isHidden = mobileMenu.classList.contains('hidden');
            
            if (isHidden) {
                mobileMenu.classList.remove('hidden');
                hamburgerIcon.classList.add('hidden');
                closeIcon.classList.remove('hidden');
            } else {
                mobileMenu.classList.add('hidden');
                hamburgerIcon.classList.remove('hidden');
                closeIcon.classList.add('hidden');
                closeAllMobileDropdowns();
            }
        });
    }

    // Desktop dropdown handlers
    if(settingsButton && settingsMenu) {
        settingsButton.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const isHidden = settingsMenu.classList.contains('hidden');
            closeAllDesktopDropdowns();
            if(isHidden) {
                settingsMenu.classList.remove('hidden');
                settingsArrow.style.transform = 'rotate(180deg)';
            }
        });
    }

    if(memberButton && memberMenu) {
        memberButton.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const isHidden = memberMenu.classList.contains('hidden');
            closeAllDesktopDropdowns();
            if(isHidden) {
                memberMenu.classList.remove('hidden');
                memberArrow.style.transform = 'rotate(180deg)';
            }
        });
    }

    if(feeButton && feeMenu) {
        feeButton.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const isHidden = feeMenu.classList.contains('hidden');
            closeAllDesktopDropdowns();
            if(isHidden) {
                feeMenu.classList.remove('hidden');
                feeArrow.style.transform = 'rotate(180deg)';
            }
        });
    }

    if(attendanceButton && attendanceMenu) {
        attendanceButton.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const isHidden = attendanceMenu.classList.contains('hidden');
            closeAllDesktopDropdowns();
            if(isHidden) {
                attendanceMenu.classList.remove('hidden');
                attendanceArrow.style.transform = 'rotate(180deg)';
            }
        });
    }

    if(trainerButton && trainerMenu) {
        trainerButton.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const isHidden = trainerMenu.classList.contains('hidden');
            closeAllDesktopDropdowns();
            if(isHidden) {
                trainerMenu.classList.remove('hidden');
                trainerArrow.style.transform = 'rotate(180deg)';
            }
        });
    }

    // Mobile dropdown handlers
    if(mobileMemberButton && mobileMemberMenu) {
        mobileMemberButton.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const isHidden = mobileMemberMenu.classList.contains('hidden');
            if(isHidden) {
                mobileMemberMenu.classList.remove('hidden');
                mobileMemberArrow.style.transform = 'rotate(180deg)';
            } else {
                mobileMemberMenu.classList.add('hidden');
                mobileMemberArrow.style.transform = 'rotate(0deg)';
            }
        });
    }

    if(mobileTrainerButton && mobileTrainerMenu) {
        mobileTrainerButton.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const isHidden = mobileTrainerMenu.classList.contains('hidden');
            if(isHidden) {
                mobileTrainerMenu.classList.remove('hidden');
                mobileTrainerArrow.style.transform = 'rotate(180deg)';
            } else {
                mobileTrainerMenu.classList.add('hidden');
                mobileTrainerArrow.style.transform = 'rotate(0deg)';
            }
        });
    }

    if(mobileFeeButton && mobileFeeMenu) {
        mobileFeeButton.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const isHidden = mobileFeeMenu.classList.contains('hidden');
            if(isHidden) {
                mobileFeeMenu.classList.remove('hidden');
                mobileFeeArrow.style.transform = 'rotate(180deg)';
            } else {
                mobileFeeMenu.classList.add('hidden');
                mobileFeeArrow.style.transform = 'rotate(0deg)';
            }
        });
    }

    if(mobileAttendanceButton && mobileAttendanceMenu) {
        mobileAttendanceButton.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const isHidden = mobileAttendanceMenu.classList.contains('hidden');
            if(isHidden) {
                mobileAttendanceMenu.classList.remove('hidden');
                mobileAttendanceArrow.style.transform = 'rotate(180deg)';
            } else {
                mobileAttendanceMenu.classList.add('hidden');
                mobileAttendanceArrow.style.transform = 'rotate(0deg)';
            }
        });
    }

    if(mobileSettingsButton && mobileSettingsMenu) {
        mobileSettingsButton.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const isHidden = mobileSettingsMenu.classList.contains('hidden');
            if(isHidden) {
                mobileSettingsMenu.classList.remove('hidden');
                mobileSettingsArrow.style.transform = 'rotate(180deg)';
            } else {
                mobileSettingsMenu.classList.add('hidden');
                mobileSettingsArrow.style.transform = 'rotate(0deg)';
            }
        });
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        // Close desktop dropdowns
        if (settingsButton && settingsMenu && !settingsButton.contains(e.target) && !settingsMenu.contains(e.target)) {
            settingsMenu.classList.add('hidden');
            settingsArrow.style.transform = 'rotate(0deg)';
        }
        if (memberButton && memberMenu && !memberButton.contains(e.target) && !memberMenu.contains(e.target)) {
            memberMenu.classList.add('hidden');
            memberArrow.style.transform = 'rotate(0deg)';
        }
        if (feeButton && feeMenu && !feeButton.contains(e.target) && !feeMenu.contains(e.target)) {
            feeMenu.classList.add('hidden');
            feeArrow.style.transform = 'rotate(0deg)';
        }
        if (attendanceButton && attendanceMenu && !attendanceButton.contains(e.target) && !attendanceMenu.contains(e.target)) {
            attendanceMenu.classList.add('hidden');
            attendanceArrow.style.transform = 'rotate(0deg)';
        }
        if (trainerButton && trainerMenu && !trainerButton.contains(e.target) && !trainerMenu.contains(e.target)) {
            trainerMenu.classList.add('hidden');
            trainerArrow.style.transform = 'rotate(0deg)';
        }

        // Close mobile menu if clicking outside
        if (mobileMenu && mobileMenuButton && !mobileMenu.contains(e.target) && !mobileMenuButton.contains(e.target)) {
            mobileMenu.classList.add('hidden');
            hamburgerIcon.classList.remove('hidden');
            closeIcon.classList.add('hidden');
            closeAllMobileDropdowns();
        }
    });
    
    // Close dropdown when pressing Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAllDesktopDropdowns();
            if (mobileMenu) {
                mobileMenu.classList.add('hidden');
                hamburgerIcon.classList.remove('hidden');
                closeIcon.classList.add('hidden');
                closeAllMobileDropdowns();
            }
        }
    });

    // Close mobile menu on window resize if switching to desktop view
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 768 && mobileMenu) { // md breakpoint
            mobileMenu.classList.add('hidden');
            hamburgerIcon.classList.remove('hidden');
            closeIcon.classList.add('hidden');
            closeAllMobileDropdowns();
        }
    });
});