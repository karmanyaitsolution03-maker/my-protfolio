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
        'photo'          => ['Profile photo (large framed portrait on the hero section)', '', 'image'],
        'first_name'     => ['First name', 'Rishabh', 'text'],
        'last_name'      => ['Last name', 'Parekh', 'text'],
        'designation'    => ['Designation', 'Backend Software Engineer', 'text'],
        'tagline'        => ['Hero tagline (HTML)', 'Backend developer focused on <b>Laravel, Symfony, REST APIs, databases, and production support</b>.', 'area'],
        'frameworks'     => ['Core frameworks', 'Laravel / Symfony', 'text'],
        'specialization' => ['Specialization', 'API Development', 'text'],
        'years'          => ['Years of experience (decimals allowed, e.g. 2.7)', '2.7', 'text'],
        'location'       => ['Location / base', 'Gujarat, India', 'text'],
        'status_label'   => ['Status label', 'Open to opportunities', 'select', [
            'Open to opportunities',
            'Open to full-time roles',
            'Open to freelance work',
            'Actively interviewing',
            'Not currently available',
        ]],
    ],

    'Contact details' => [
        'email'            => ['Email', 'rishabh.parekh@example.com', 'text'],
        'linkedin'         => ['LinkedIn URL', 'https://www.linkedin.com', 'text'],
        'linkedin_label'   => ['LinkedIn label', 'linkedin.com/in/rishabh-parekh', 'text'],
        'whatsapp_number'  => ['WhatsApp number (digits only, with country code, e.g. 919313039898)', '919313039898', 'text'],
        'whatsapp_label'   => ['WhatsApp label', 'Chat on WhatsApp', 'text'],
        'response_time'    => ['Response time', '< 24 HOURS', 'text'],
        'timezone'         => ['Timezone', 'IST (UTC +5:30)', 'text'],
    ],

    'About (AI assessment)' => [
        'about_1' => ['About paragraph 1 (HTML)', '<b>Backend Software Engineer</b> with hands-on experience in Laravel, Symfony, REST APIs, database design, client communication, and production support.', 'area'],
        'about_2' => ['About paragraph 2 (HTML)', 'Comfortable turning requirements into reliable features, debugging live issues, coordinating deployments, and keeping projects moving with clear communication.', 'area'],
        'about_3' => ['About paragraph 3 (HTML)', 'Focused on clean backend logic, practical architecture, and dependable delivery for real business products.', 'area'],
    ],

    'AI intro — boot screen' => [
        'intro_greeting_1' => ['Greeting line 1', 'Hello, Visitor.', 'text'],
        'intro_greeting_2' => ['Greeting line 2', 'Welcome to the portfolio.', 'text'],
        'intro_guide'      => ['Guide line (waves)', 'I will guide you through the highlights.', 'text'],
        'intro_pitch'      => ['Pitch line', 'This portfolio presents backend experience, technical skills, production projects, and ways to connect.', 'area'],
        'intro_diagnostics'=> ['Diagnostics — one per line "LABEL | RESULT" (★ in result = gold)',
            "BACKEND ENGINEERING EXPERTISE | DETECTED ✓\n"
            . "API DEVELOPMENT EXPERTISE | DETECTED ✓\n"
            . "SYSTEM ARCHITECTURE KNOWLEDGE | CONFIRMED ✓\n"
            . "PROJECT DELIVERY EXPERIENCE | VERIFIED ✓\n"
            . "PROBLEM SOLVING CAPABILITIES | CONFIRMED ✓\n"
            . "PROFESSIONAL RELIABILITY SCORE | EXCEPTIONAL ★", 'area'],
        'intro_validated'  => ['Validation line', 'Profile overview ready.', 'text'],
        'intro_welcome'    => ['Welcome / launch line', 'Please welcome :name.', 'text'],
        'intro_voice'       => ['Chat-bot voice — the floating robot speaks aloud via OpenAI TTS (1 = on, 0 = off)', '1', 'text'],
        'intro_voice_lang'  => ['Voice INPUT language code for the mic button (e.g. en-US, en-GB, en-IN, hi-IN) — spoken output is always the "nova" OpenAI voice', 'en-US', 'text'],
    ],

    'Hero / Command Deck' => [
        'hero_coord'         => ['Coordinate strip', 'PORTFOLIO OVERVIEW / BACKEND ENGINEER / AVAILABLE FOR WORK', 'text'],
        'hero_cta_primary'   => ['Primary button', 'VIEW PROFILE', 'text'],
        'hero_cta_secondary' => ['Secondary button', 'CONTACT ME', 'text'],
        'hero_scroll'        => ['Scroll hint', 'SCROLL TO EXPLORE', 'text'],
        'hero_chips'         => ['Floating chips — one per line (HTML)',
            "<b>BACKEND</b> Laravel / Symfony\n"
            . "<b>APIS</b> REST integration and auth\n"
            . "<b>DATABASE</b> MySQL and MongoDB\n"
            . "<b>SUPPORT</b> Debugging and deployment", 'area'],
    ],

    'Section · Profile' => [
        'profile_tag'          => ['Tag', 'MODULE 01 · AI PROFILE REPORT', 'text'],
        'profile_title'        => ['Title', 'Profile overview:', 'text'],
        'profile_title_hl'     => ['Title highlight', 'backend focused.', 'text'],
        'profile_identity_tag' => ['Identity matrix tag', '// IDENTITY MATRIX — VERIFIED', 'text'],
        'profile_heading'      => ['Assessment heading', 'Professional summary', 'text'],
        'profile_note'         => ['Report footer note (HTML)', 'CORE STRENGTHS: <b>APIS</b> / BACKEND LOGIC / DATABASES / PRODUCTION SUPPORT', 'area'],
    ],

    'Section · Skills' => [
        'skills_tag'      => ['Tag', 'MODULE 02 · ACTIVE SKILL MODULES', 'text'],
        'skills_title'    => ['Title', 'Technical skills.', 'text'],
        'skills_title_hl' => ['Title highlight', 'Production ready.', 'text'],
        'skills_sub'      => ['Subtitle', 'A practical backend toolkit covering APIs, databases, frameworks, communication, and project delivery.', 'area'],
    ],

    'Section · Experience' => [
        'exp_tag'      => ['Tag', 'MODULE 03 · EXPERIENCE DATABASE', 'text'],
        'exp_title'    => ['Title', 'Mission logs:', 'text'],
        'exp_title_hl' => ['Title highlight (:exp = count)', ':exp records retrieved.', 'text'],
    ],

    'Section · Projects' => [
        'projects_tag'      => ['Tag', 'MODULE 04 · PROJECT COMMAND CENTER', 'text'],
        'projects_title'    => ['Title', 'Selected projects.', 'text'],
        'projects_title_hl' => ['Title highlight', 'Built for real users.', 'text'],
        'projects_sub'      => ['Subtitle (:name = your name)', 'A quick look at the platforms, APIs, and backend flows :name has worked on.', 'area'],
    ],

    'Section · Achievements' => [
        'ach_tag'      => ['Tag', 'MODULE 05 · ACHIEVEMENT TERMINAL', 'text'],
        'ach_title'    => ['Title', 'Query results:', 'text'],
        'ach_title_hl' => ['Title highlight (:ach = count)', ':ach records. All verified.', 'text'],
    ],

    'Section · Career & Availability' => [
        'career_show'        => ['Show this section on the site (1 = show, 0 = hide)', '1', 'text'],
        'career_tag'         => ['Tag', 'MODULE 07 · CAREER & AVAILABILITY', 'text'],
        'career_title'       => ['Title', 'Availability status:', 'text'],
        'career_title_hl'    => ['Title highlight', 'ready to deploy.', 'text'],
        'career_identity_tag'=> ['Matrix tag', '// AVAILABILITY MATRIX — VERIFIED', 'text'],
        'career_heading'     => ['Body heading', 'Career snapshot', 'text'],
        'career_note'        => ['Additional note (HTML)', 'I have experience handling both web and mobile application projects. I have independently developed and managed projects such as <b>BizInvoice</b>, which has strengthened my ability to work across both platforms and deliver complete solutions.', 'area'],
        // The left-side rows (Current CTC, Notice Period, …) are managed under
        // Admin → Availability, and the ✓ highlight points under Admin → Career Points.
    ],

    'Section · SEO / Sharing' => [
        'og_image' => ['Social share image (og:image) — shown when the link is shared on LinkedIn/WhatsApp/etc, recommended 1200x630px', '', 'image'],
    ],

    'Section · Contact' => [
        'contact_tag'           => ['Tag', 'MODULE 06 · CONTACT COMMAND CENTER', 'text'],
        'contact_title'         => ['Title', 'Open a channel.', 'text'],
        'contact_title_hl'      => ['Title highlight', 'The system is listening.', 'text'],
        'contact_heading'       => ['Left heading', 'Let us work together.', 'text'],
        'contact_text'          => ['Left paragraph', 'For Laravel, Symfony, API, backend, or production support work, send a message with the project details.', 'area'],
        'contact_ai_1'          => ['AI line 1', 'Communication channels established.', 'text'],
        'contact_ai_2'          => ['AI line 2', 'Awaiting connection request…', 'text'],
        'contact_label_name'    => ['Form label — name', 'CALLSIGN / NAME', 'text'],
        'contact_label_email'   => ['Form label — email', 'RETURN FREQUENCY / EMAIL', 'text'],
        'contact_label_message' => ['Form label — message', 'MISSION BRIEF / MESSAGE', 'text'],
        'contact_btn'           => ['Submit button', 'TRANSMIT MESSAGE ▸▸▸', 'text'],
        'contact_btn_sent'      => ['Submit button — sent', '✓ TRANSMISSION RECEIVED', 'text'],
        'resume_label'          => ['Resume link label', 'DOWNLOAD RESUME — SUBJECT_FILE.txt', 'text'],
        'resume_file'           => ['Resume PDF (upload to let visitors download it)', '', 'file'],
    ],

    'Success modal' => [
        'complete_tag'   => ['Small tag', 'TRANSMISSION SUCCESSFUL', 'text'],
        'complete_title' => ['Title', 'CONNECTION ESTABLISHED', 'text'],
        'complete_text'  => ['Body text', "Your message is en route to the command center. Expect a reply within 24 hours. :first's Assistant thanks you for exploring the system, commander.", 'area'],
        'complete_btn'   => ['Button', 'RETURN TO COMMAND DECK ↩', 'text'],
    ],

    'Navigation / HUD / Footer' => [
        'hud_status'    => ['HUD status', 'ASSISTANT ONLINE', 'text'],
        'nav_waypoints' => ['Waypoint labels — 8 lines (deck → availability)',
            "COMMAND DECK\nPROFILE REPORT\nSKILL MODULES\nEXPERIENCE DB\nPROJECT CENTER\nACHIEVEMENTS\nCOMMS\nAVAILABILITY", 'area'],
        'hud_sectors'   => ['HUD sector names — 8 lines (deck → availability)',
            "MODULE 00 — COMMAND DECK\nMODULE 01 — PROFILE REPORT\nMODULE 02 — SKILL MODULES\nMODULE 03 — EXPERIENCE DATABASE\nMODULE 04 — PROJECT CENTER\nMODULE 05 — ACHIEVEMENT TERMINAL\nMODULE 06 — COMMS\nMODULE 07 — CAREER & AVAILABILITY", 'area'],
        'footer_brand'  => ['Footer brand', 'AI COMMAND CENTER', 'text'],
        'footer_status' => ['Footer status', 'ALL MODULES NOMINAL · ASSISTANT v4.0', 'text'],
        'footer_back'   => ['Footer back-to-top', 'RETURN TO COMMAND DECK ↑', 'text'],
    ],

    'AI assistant (floating bot)' => [
        'assistant_lines' => ['Per-section lines — 8 lines (HTML)',
            "Welcome to the command deck, commander. 🛰️ I'm <b>:first's Assistant</b> — scroll, and I'll walk you through the subject file.\n"
            . "My full analysis of :name. Confidence level: <b>99.7%</b>. I don't say that about many humans.\n"
            . "Five skill modules, <b>all active</b>. The energy readings are from real production systems.\n"
            . "The experience database — :exp mission logs, one of them <b>still transmitting live</b>.\n"
            . "Watch the data flow! Every system here is <b>running in production</b> right now.\n"
            . "Achievement query complete: <b>:ach records, all verified</b>. My database doesn't lie. 🏆\n"
            . "<b>Communication channels established.</b> Awaiting connection request… 📡\n"
            . "Availability matrix decoded: :name is <b>open to opportunities</b>. Notice period and CTC are all on file. 🚀", 'area'],
        'assistant_idle'  => ['Idle tips — one per line (HTML)',
            "Still analyzing, commander? The <b>waypoints</b> on the right jump between modules instantly.\n"
            . "Fun fact: I run on <b>coffee telemetry</b> from :first's keyboard. Readings are high.\n"
            . "The <b>resume download</b> in Module 06 contains the complete subject file. 📄\n"
            . "Tip: hover the project cards — the <b>data flows</b> respond to attention. Like me.", 'area'],
        'assistant_focus'  => ['On contact focus (HTML)', 'Incoming transmission detected! Boosting signal… 📡', 'text'],
        'assistant_resume' => ['On resume click (HTML)', 'Subject file transferred. Classification: <b>highly recommendable</b>. 📄✨', 'text'],
        'assistant_suggestions' => ['Quick-reply suggestions shown when the chat opens — one per line (max 5 used)',
            "What are his core skills?\n"
            . "Tell me about his work experience\n"
            . "Show me his projects\n"
            . "Is he available for freelance work?\n"
            . "How can I contact him?", 'area'],
    ],

];
