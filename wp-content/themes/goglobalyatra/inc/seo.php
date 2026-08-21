<?php
/**
 * SEO & AI-crawler visibility helpers: dynamic llms.txt and explicit
 * allow-rules for AI bots in robots.txt (in addition to Yoast's sitemap).
 */

if (!defined('ABSPATH')) {
    exit;
}

/** Serve /llms.txt as a plain virtual endpoint (checked on 'init', like core's robots.txt handling, so it never gets caught by redirect_canonical's trailing-slash logic). */
function goglobalyatra_maybe_serve_llms_txt(): void
{
    $path = parse_url((string) ($_SERVER['REQUEST_URI'] ?? ''), PHP_URL_PATH);
    if ($path !== '/llms.txt') {
        return;
    }

    header('Content-Type: text/plain; charset=utf-8');

    $lines = [];
    $lines[] = '# ' . get_bloginfo('name');
    $lines[] = get_bloginfo('description');
    $lines[] = '';
    $lines[] = '## Core Services';
    $lines[] = '- Inbound Nepal travel packages and outbound international tours';
    $lines[] = '- Expert local and international destination knowledge';
    $lines[] = '- Transparent pricing and personalized service';
    $lines[] = '- Flexible packages for families, groups, and corporate travelers';
    $lines[] = '';
    $lines[] = '## Key Pages';
    $lines[] = '- [Home](' . home_url('/') . ')';
    $lines[] = '- [About Us](' . home_url('/about/') . ')';
    $lines[] = '- [Contact](' . home_url('/contact/') . ')';
    $lines[] = '- [Traveler Stories / Testimonials](' . home_url('/testimonials/') . ')';

    foreach (['outbound' => 'Outbound Travel Packages', 'inbound' => 'Inbound Nepal Packages'] as $slug => $heading) {
        $packages = get_posts([
            'post_type' => 'travel',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
            'tax_query' => [['taxonomy' => 'package_type', 'field' => 'slug', 'terms' => $slug]],
        ]);

        if ($packages === []) {
            continue;
        }

        $lines[] = '';
        $lines[] = '## ' . $heading;
        foreach ($packages as $package) {
            $duration = (string) get_field('duration', $package->ID);
            $summary = get_the_excerpt($package);
            $lines[] = '- [' . $package->post_title . '](' . get_permalink($package) . ')' . ($duration ? ' - ' . $duration : '') . ($summary ? ': ' . $summary : '');
        }
    }

    echo implode("\n", $lines) . "\n";
    exit;
}
add_action('init', 'goglobalyatra_maybe_serve_llms_txt', 20);

/** Explicitly allow known AI/LLM crawlers alongside the default Yoast robots.txt rules. */
function goglobalyatra_robots_txt(string $output): string
{
    $ai_crawlers = [
        'GPTBot', 'ChatGPT-User', 'OAI-SearchBot', // OpenAI
        'ClaudeBot', 'anthropic-ai', 'Claude-Web',  // Anthropic
        'PerplexityBot', 'Perplexity-User',         // Perplexity
        'Google-Extended',                          // Google Gemini training
        'Bytespider',                                // ByteDance
        'CCBot',                                     // Common Crawl (used by many LLMs)
        'Applebot-Extended',                         // Apple Intelligence
    ];

    $extra = "\n# AI / LLM crawlers explicitly allowed\n";
    foreach ($ai_crawlers as $bot) {
        $extra .= "User-agent: {$bot}\nAllow: /\n";
    }
    $extra .= "\n# Machine-readable content summary for LLMs: " . home_url('/llms.txt') . "\n";

    return rtrim($output) . "\n" . $extra;
}
add_filter('robots_txt', 'goglobalyatra_robots_txt', 20);
