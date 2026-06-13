<?php

/*
|--------------------------------------------------------------------------
| Portfolio content — single source of truth
|--------------------------------------------------------------------------
| Every editable text string on the site lives here, grouped for the admin
| Settings page. Each field is:  'key' => [ 'Label', 'default value', 'type' ]
| type: 'text' (single line) or 'area' (multi-line textarea).
|
| Tokens you can use inside any value (replaced live on render):
|   :name   → full name        :first → first name      :last → last name
|   :exp    → experience count  :proj  → project count   :ach  → achievement count
|
| Multi-line fields (hero_chips, *_diagnostics, nav_*, assistant_*) take ONE
| entry per line. Values render as HTML, so <b>…</b> etc. are allowed.
*/

return [

    'Identity' => [
        'first_name'     => ['First name', 'Rishabh', 'text'],
        'last_name'      => ['Last name', 'Parekh', 'text'],
        'designation'    => ['Designation', 'Backend Software Engineer', 'text'],
        'tagline'        => ['Hero tagline (HTML)', 'Building <b>scalable systems, APIs, and digital experiences</b> that power modern products.', 'area'],
        'frameworks'     => ['Core frameworks', 'Laravel · Symfony', 'text'],
        'specialization' => ['Specialization', 'API Development', 'text'],
        'years'          => ['Years of experience', '2', 'text'],
        'location'       => ['Location / base', 'Gujarat, India', 'text'],
        'status_label'   => ['Status label', 'Open to opportunities', 'text'],
    ],

    'Contact details' => [
        'email'          => ['Email', 'rishabh.parekh@example.com', 'text'],
        'linkedin'       => ['LinkedIn URL', 'https://www.linkedin.com', 'text'],
        'linkedin_label' => ['LinkedIn label', 'linkedin.com/in/rishabh-parekh', 'text'],
        'response_time'  => ['Response time', '< 24 HOURS', 'text'],
        'timezone'       => ['Timezone', 'IST (UTC +5:30)', 'text'],
    ],

    'About (AI assessment)' => [
        'about_1' => ['About paragraph 1 (HTML)', 'Subject is a <b>backend software engineer</b> with hands-on experience across backend development, <b>API integration, client delivery, project coordination and production support</b>.', 'area'],
        'about_2' => ['About paragraph 2 (HTML)', 'Demonstrated ability to build <b>scalable applications</b> and maintain <b>reliable systems</b> under real production conditions — including direct client communication, requirement gathering and deployment coordination.', 'area'],
        'about_3' => ['About paragraph 3 (HTML)', 'Behavioral analysis indicates an unusual calm during production incidents. This trait is rare and valuable.', 'area'],
    ],

    'AI intro — boot screen' => [
        'intro_greeting_1' => ['Greeting line 1', 'Hello, Visitor.', 'text'],
        'intro_greeting_2' => ['Greeting line 2', 'Welcome to Command Center.', 'text'],
        'intro_guide'      => ['Guide line (waves)', 'I am your AI guide.', 'text'],
        'intro_pitch'      => ['Pitch line', 'Today I will introduce a software engineer who transforms complex challenges into scalable digital solutions.', 'area'],
        'intro_diagnostics'=> ['Diagnostics — one per line "LABEL | RESULT" (★ in result = gold)',
            "BACKEND ENGINEERING EXPERTISE | DETECTED ✓\n"
            . "API DEVELOPMENT EXPERTISE | DETECTED ✓\n"
            . "SYSTEM ARCHITECTURE KNOWLEDGE | CONFIRMED ✓\n"
            . "PROJECT DELIVERY EXPERIENCE | VERIFIED ✓\n"
            . "PROBLEM SOLVING CAPABILITIES | CONFIRMED ✓\n"
            . "PROFESSIONAL RELIABILITY SCORE | EXCEPTIONAL ★", 'area'],
        'intro_validated'  => ['Validation line', 'Profile validation completed.', 'text'],
        'intro_welcome'    => ['Welcome / launch line', 'Please welcome…', 'text'],
        'intro_voice'       => ['Chat-bot voice — the floating robot speaks aloud (1 = on, 0 = off)', '1', 'text'],
        'intro_voice_lang'  => ['Bot voice language code (e.g. en-US, en-GB, en-IN, hi-IN)', 'en-US', 'text'],
        'intro_voice_gender'=> ['Preferred bot voice (female / male / any)', 'male', 'text'],
        'intro_voice_name'  => ['Exact voice name (optional — overrides gender; e.g. "Google UK English Female", "Microsoft Zira")', '', 'text'],
    ],

    'Hero / Command Deck' => [
        'hero_coord'         => ['Coordinate strip', 'AI COMMAND CENTER · SUBJECT FILE 0001 · CLEARANCE: PUBLIC', 'text'],
        'hero_cta_primary'   => ['Primary button', 'OPEN PROFILE REPORT ↓', 'text'],
        'hero_cta_secondary' => ['Secondary button', 'ESTABLISH COMMS', 'text'],
        'hero_scroll'        => ['Scroll hint', 'SCROLL TO EXPLORE', 'text'],
        'hero_chips'         => ['Floating chips — one per line (HTML)',
            "<b>API</b> /v1/systems · 200 OK · 38ms\n"
            . "<b>QUEUE</b> 1,284 jobs · processed ✓\n"
            . "<b>DB</b> mysql://core · 0.003s\n"
            . "<b>UPTIME</b> 99.9% · nominal", 'area'],
    ],

    'Section · Profile' => [
        'profile_tag'          => ['Tag', 'MODULE 01 · AI PROFILE REPORT', 'text'],
        'profile_title'        => ['Title', 'Subject analysis:', 'text'],
        'profile_title_hl'     => ['Title highlight', 'complete.', 'text'],
        'profile_identity_tag' => ['Identity matrix tag', '// IDENTITY MATRIX — VERIFIED', 'text'],
        'profile_heading'      => ['Assessment heading', 'A.R.I.A. assessment', 'text'],
        'profile_note'         => ['Report footer note (HTML)', 'ANALYSIS CONFIDENCE: <b>99.7%</b> · FALSE-POSITIVE PROBABILITY: NEGLIGIBLE · REPORT GENERATED BY A.R.I.A.', 'area'],
    ],

    'Section · Skills' => [
        'skills_tag'      => ['Tag', 'MODULE 02 · ACTIVE SKILL MODULES', 'text'],
        'skills_title'    => ['Title', 'Five modules.', 'text'],
        'skills_title_hl' => ['Title highlight', 'All systems active.', 'text'],
        'skills_sub'      => ['Subtitle', 'Energy levels reflect real production mileage — neural links show how the modules work as one system.', 'area'],
    ],

    'Section · Experience' => [
        'exp_tag'      => ['Tag', 'MODULE 03 · EXPERIENCE DATABASE', 'text'],
        'exp_title'    => ['Title', 'Mission logs:', 'text'],
        'exp_title_hl' => ['Title highlight (:exp = count)', ':exp records retrieved.', 'text'],
    ],

    'Section · Projects' => [
        'projects_tag'      => ['Tag', 'MODULE 04 · PROJECT COMMAND CENTER', 'text'],
        'projects_title'    => ['Title', 'Five systems.', 'text'],
        'projects_title_hl' => ['Title highlight', 'All running in production.', 'text'],
        'projects_sub'      => ['Subtitle (:name = your name)', 'Live architecture feeds below — watch the data flow through each system :name built.', 'area'],
    ],

    'Section · Achievements' => [
        'ach_tag'      => ['Tag', 'MODULE 05 · ACHIEVEMENT TERMINAL', 'text'],
        'ach_title'    => ['Title', 'Query results:', 'text'],
        'ach_title_hl' => ['Title highlight (:ach = count)', ':ach records. All verified.', 'text'],
    ],

    'Section · Contact' => [
        'contact_tag'           => ['Tag', 'MODULE 06 · CONTACT COMMAND CENTER', 'text'],
        'contact_title'         => ['Title', 'Open a channel.', 'text'],
        'contact_title_hl'      => ['Title highlight', 'The system is listening.', 'text'],
        'contact_heading'       => ['Left heading', 'Transmit your brief.', 'text'],
        'contact_text'          => ['Left paragraph', 'New API, Laravel product, Symfony service, or a production system that needs a steady pair of hands — send coordinates.', 'area'],
        'contact_ai_1'          => ['AI line 1', 'Communication channels established.', 'text'],
        'contact_ai_2'          => ['AI line 2', 'Awaiting connection request…', 'text'],
        'contact_label_name'    => ['Form label — name', 'CALLSIGN / NAME', 'text'],
        'contact_label_email'   => ['Form label — email', 'RETURN FREQUENCY / EMAIL', 'text'],
        'contact_label_message' => ['Form label — message', 'MISSION BRIEF / MESSAGE', 'text'],
        'contact_btn'           => ['Submit button', 'TRANSMIT MESSAGE ▸▸▸', 'text'],
        'contact_btn_sent'      => ['Submit button — sent', '✓ TRANSMISSION RECEIVED', 'text'],
        'resume_label'          => ['Resume link label', 'DOWNLOAD RESUME — SUBJECT_FILE.txt', 'text'],
    ],

    'Success modal' => [
        'complete_tag'   => ['Small tag', 'TRANSMISSION SUCCESSFUL', 'text'],
        'complete_title' => ['Title', 'CONNECTION ESTABLISHED', 'text'],
        'complete_text'  => ['Body text', 'Your message is en route to the command center. Expect a reply within 24 hours. A.R.I.A. thanks you for exploring the system, commander.', 'area'],
        'complete_btn'   => ['Button', 'RETURN TO COMMAND DECK ↩', 'text'],
    ],

    'Navigation / HUD / Footer' => [
        'hud_status'    => ['HUD status', 'A.R.I.A. ONLINE', 'text'],
        'nav_waypoints' => ['Waypoint labels — 7 lines (deck → comms)',
            "COMMAND DECK\nPROFILE REPORT\nSKILL MODULES\nEXPERIENCE DB\nPROJECT CENTER\nACHIEVEMENTS\nCOMMS", 'area'],
        'hud_sectors'   => ['HUD sector names — 7 lines (deck → comms)',
            "MODULE 00 — COMMAND DECK\nMODULE 01 — PROFILE REPORT\nMODULE 02 — SKILL MODULES\nMODULE 03 — EXPERIENCE DATABASE\nMODULE 04 — PROJECT CENTER\nMODULE 05 — ACHIEVEMENT TERMINAL\nMODULE 06 — COMMS", 'area'],
        'footer_brand'  => ['Footer brand', 'AI COMMAND CENTER', 'text'],
        'footer_status' => ['Footer status', 'ALL MODULES NOMINAL · A.R.I.A. v4.0', 'text'],
        'footer_back'   => ['Footer back-to-top', 'RETURN TO COMMAND DECK ↑', 'text'],
    ],

    'AI assistant (floating bot)' => [
        'assistant_lines' => ['Per-section lines — 7 lines (HTML)',
            "Welcome to the command deck, commander. 🛰️ I'm <b>A.R.I.A.</b> — scroll, and I'll walk you through the subject file.\n"
            . "My full analysis of :name. Confidence level: <b>99.7%</b>. I don't say that about many humans.\n"
            . "Five skill modules, <b>all active</b>. The energy readings are from real production systems.\n"
            . "The experience database — :exp mission logs, one of them <b>still transmitting live</b>.\n"
            . "Watch the data flow! Every system here is <b>running in production</b> right now.\n"
            . "Achievement query complete: <b>:ach records, all verified</b>. My database doesn't lie. 🏆\n"
            . "<b>Communication channels established.</b> Awaiting connection request… 📡", 'area'],
        'assistant_idle'  => ['Idle tips — one per line (HTML)',
            "Still analyzing, commander? The <b>waypoints</b> on the right jump between modules instantly.\n"
            . "Fun fact: I run on <b>coffee telemetry</b> from :first's keyboard. Readings are high.\n"
            . "The <b>resume download</b> in Module 06 contains the complete subject file. 📄\n"
            . "Tip: hover the project cards — the <b>data flows</b> respond to attention. Like me.", 'area'],
        'assistant_click'  => ['On click (HTML)', "Beep! 🤖 Query me anytime. Or jump to <b>Module 06</b> to open a channel with :first.", 'text'],
        'assistant_focus'  => ['On contact focus (HTML)', 'Incoming transmission detected! Boosting signal… 📡', 'text'],
        'assistant_resume' => ['On resume click (HTML)', 'Subject file transferred. Classification: <b>highly recommendable</b>. 📄✨', 'text'],
    ],

];
