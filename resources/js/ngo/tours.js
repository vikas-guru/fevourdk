/**
 * Central registry of first-run guided tours for the NGO workspace
 * (+ donor & volunteer dashboards). One place for every tour's copy.
 *
 * Each entry: pageKey -> { storageKey, steps: [{ selector, title, body }] }
 * Selectors target [data-tour="…"] anchors placed on the page. Missing
 * anchors are skipped gracefully by DashboardTour.focusStep().
 *
 * Bump the _v# in a storageKey to re-show a tour after a meaningful change.
 */
export const TOURS = {
    dashboard: {
        storageKey: 'ngo_dashboard_tour_v1',
        steps: [
            { selector: '[data-tour="hero"]', title: 'Your organisation, front and centre', body: 'This is your branded workspace. Your logo, verification status, and headline impact numbers live here. The gold button lets you customise your public website anytime.' },
            { selector: '[data-tour="ribbon"]', title: 'Impact at a glance', body: 'Total raised, this month, community reach, and feed views — your four headline numbers, always live and real.' },
            { selector: '[data-tour="stats"]', title: 'Everything you manage', body: 'Campaigns, donations, community, feed reach, ledger balance, field teams, and assets. Tap any card to jump straight to that tool.' },
            { selector: '[data-tour="money"]', title: 'Full money transparency', body: 'Every rupee in and out, straight from your ledger. Donations come in as credits; expenses and payouts as debits — nothing hidden.' },
            { selector: '[data-tour="post"]', title: 'Share your impact', body: 'Post an update and it goes live to the community feed — growing your reach and the numbers up top. That is the whole loop!' },
        ],
    },

    analytics: {
        storageKey: 'ngo_tour_analytics_v1',
        steps: [
            { selector: '[data-tour="intro"]', title: 'Your impact, measured', body: 'Analytics turns your real activity — donations, campaigns, feed reach — into trends you can act on. Everything here is your own live data.' },
            { selector: '[data-tour="metrics"]', title: 'Headline numbers', body: 'The key totals at the top give you the pulse of your organisation at a glance.' },
            { selector: '[data-tour="charts"]', title: 'Trends over time', body: 'See how giving and reach move month to month so you can spot what is working.' },
        ],
    },

    campaigns: {
        storageKey: 'ngo_tour_campaigns_v1',
        steps: [
            { selector: '[data-tour="intro"]', title: 'Run focused appeals', body: 'Campaigns let you raise for a specific cause — a borewell, school kits, a medical fund — each with its own goal and progress.' },
            { selector: '[data-tour="create"]', title: 'Start a new campaign', body: 'Give it a title, goal amount, and story. Donors can give directly to it from your public site.' },
            { selector: '[data-tour="list"]', title: 'Track every campaign', body: 'See how much each campaign has raised against its goal, and keep supporters updated.' },
        ],
    },

    donations: {
        storageKey: 'ngo_tour_donations_v1',
        steps: [
            { selector: '[data-tour="intro"]', title: 'Every gift, tracked', body: 'This is the record of donations received through your own payment gateway. The platform never holds your money — it flows straight to you.' },
            { selector: '[data-tour="summary"]', title: 'Totals at a glance', body: 'Lifetime and recent giving, summarised, so you always know where you stand.' },
            { selector: '[data-tour="list"]', title: 'Donor records', body: 'Each donation with donor, amount, and date — ready for receipts and your ledger.' },
        ],
    },

    'finance-hub': {
        storageKey: 'ngo_tour_finance_v1',
        steps: [
            { selector: '[data-tour="intro"]', title: 'Your treasury, in one place', body: 'Balances, cashbook, and settings. Money in (donations) and money out (salaries, vendors, claims) all reconcile against your ledger.' },
            { selector: '[data-tour="balances"]', title: 'Live balances', body: 'What you have, across your linked accounts — updated as movements post.' },
            { selector: '[data-tour="actions"]', title: 'Pay & record', body: 'Pay staff or vendors, approve expense claims — every action auto-posts to the transparent ledger.' },
        ],
    },

    'feed-studio': {
        storageKey: 'ngo_tour_feedstudio_v1',
        steps: [
            { selector: '[data-tour="intro"]', title: 'See how your posts perform', body: 'Feed Studio is your post analytics — track the reach and engagement of everything you share with the community.' },
            { selector: '[data-tour="compose"]', title: 'Your posts at a glance', body: 'Each post with its views and reactions, so you can learn what resonates. Use “Post an update” from the menu to publish a new one.' },
        ],
    },

    'post-update': {
        storageKey: 'ngo_tour_postupdate_v1',
        steps: [
            { selector: '[data-tour="intro"]', title: 'Share an update', body: 'A quick way to post what is happening on the ground straight to the community feed.' },
            { selector: '[data-tour="compose"]', title: 'Write your update', body: 'Keep it human — what you did, who it helped. Photos make it land.' },
            { selector: '[data-tour="submit"]', title: 'Go live', body: 'Publish and supporters see it instantly, lifting your feed reach on the dashboard.' },
        ],
    },

    profile: {
        storageKey: 'ngo_tour_profile_v1',
        steps: [
            { selector: '[data-tour="intro"]', title: 'Your organisation profile', body: 'The details here power your public presence — name, focus areas, contact, and verification.' },
            { selector: '[data-tour="details"]', title: 'Keep it current', body: 'Accurate details build trust with donors and help you get verified faster.' },
        ],
    },

    digitalization: {
        storageKey: 'ngo_tour_digitalization_v1',
        steps: [
            { selector: '[data-tour="intro"]', title: 'Your website, no code needed', body: 'Edit your public website in plain language on the left and watch it update live on the right — no technical skills required.' },
            { selector: '[data-tour="editor"]', title: 'Edit in sections', body: 'Look & feel, welcome message, mission, key facts, stories, donations, contact — open any section and change the words or photos.' },
            { selector: '[data-tour="preview"]', title: 'Live preview', body: 'Your real site, updating as you save. What you see here is exactly what visitors see.' },
        ],
    },

    ledger: {
        storageKey: 'ngo_tour_ledger_v1',
        steps: [
            { selector: '[data-tour="intro"]', title: 'Radical transparency', body: 'The ledger is your single source of financial truth — every credit and debit, in order, nothing hidden.' },
            { selector: '[data-tour="balance"]', title: 'Running balance', body: 'Your current balance is the sum of every entry. Donations credit it; spending debits it.' },
            { selector: '[data-tour="entries"]', title: 'Record an entry', body: 'Log income or expenses here. Many entries post automatically from donations and finance actions.' },
        ],
    },

    field: {
        storageKey: 'ngo_tour_field_v1',
        steps: [
            { selector: '[data-tour="intro"]', title: 'Coordinate the ground team', body: 'Site visits and tasks — assign work, see it on the map, and track live routes from the field.' },
            { selector: '[data-tour="map"]', title: 'See it on the map', body: 'Tasks and visits placed geographically so you can plan routes and coverage.' },
            { selector: '[data-tour="tasks"]', title: 'Assign & follow up', body: 'Create a task, assign it to a team member, and watch it move to done.' },
        ],
    },

    office: {
        storageKey: 'ngo_tour_office_v1',
        steps: [
            { selector: '[data-tour="intro"]', title: 'Office & assets', body: 'Track what your organisation owns — fixed assets and consumables — so nothing gets lost.' },
            { selector: '[data-tour="inventory"]', title: 'Your inventory', body: 'Add items, mark stock levels, and get a heads-up when consumables run low.' },
        ],
    },

    hr: {
        storageKey: 'ngo_tour_hr_v1',
        steps: [
            { selector: '[data-tour="intro"]', title: 'People & teams', body: 'One login for your whole HRMS — your people, their leave, and field visits in one place.' },
            { selector: '[data-tour="team"]', title: 'Your team', body: 'Add employees, set roles, and keep contact details current.' },
            { selector: '[data-tour="leave"]', title: 'Leave & attendance', body: 'Staff request leave; you approve — all logged for clean records.' },
        ],
    },

    'donor-dashboard': {
        storageKey: 'tour_donor_dashboard_v1',
        steps: [
            { selector: '[data-tour="intro"]', title: 'Welcome to your giving home', body: 'Everything you support, in one place — your donations, the causes you follow, and their impact.' },
            { selector: '[data-tour="giving"]', title: 'Your giving', body: 'See what you have donated and to whom, with a clear running history.' },
            { selector: '[data-tour="follow"]', title: 'Causes you follow', body: 'Updates from the NGOs you follow land here, so you can see your impact unfold.' },
        ],
    },

    'volunteer-feed': {
        storageKey: 'tour_volunteer_feed_v1',
        steps: [
            { selector: '[data-tour="intro"]', title: 'The community feed', body: 'Real updates from NGOs across the platform — see impact as it happens and engage with causes you care about.' },
            { selector: '[data-tour="feed"]', title: 'Follow along', body: 'Like and follow organisations to shape what you see and stay close to the work.' },
        ],
    },
}

export function getTour(pageKey) {
    return TOURS[pageKey] || null
}
