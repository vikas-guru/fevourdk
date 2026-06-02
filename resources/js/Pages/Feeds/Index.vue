<template>
    <AppLayout hide-chrome-mobile>
        <div class="ff">
            <!-- ============================ HERO ============================ -->
            <section class="ff-hero">
                <div class="ff-hero__glow" aria-hidden="true" />
                <div class="ff-grain" aria-hidden="true" />
                <div class="ff-hero__rings" aria-hidden="true">
                    <span class="ff-ring ff-ring--1" />
                    <span class="ff-ring ff-ring--2" />
                </div>

                <div class="ff-hero__inner">
                    <p class="ff-eyebrow">
                        <span class="ff-eyebrow__dot" />
                        Public · Live community
                    </p>
                    <h1 class="ff-display ff-hero__title">
                        Stories from
                        <span class="ff-hero__accent">the field.</span>
                    </h1>
                    <p class="ff-hero__lede">
                        Real updates, reactions and conversations from verified Karnataka
                        organisations — watch impact unfold, then join in.
                    </p>

                    <div class="ff-hero__stats">
                        <div class="ff-chip">
                            <span class="ff-chip__num ff-display">{{ postCount }}</span>
                            <span class="ff-chip__lbl">Posts live</span>
                        </div>
                        <div class="ff-chip">
                            <span class="ff-chip__num ff-display ff-chip__num--gold">{{ ngoCount }}</span>
                            <span class="ff-chip__lbl">Verified NGOs</span>
                        </div>
                        <div class="ff-chip">
                            <span class="ff-chip__num ff-display ff-chip__num--emerald">{{ filteredNgos.length }}</span>
                            <span class="ff-chip__lbl">On the map</span>
                        </div>
                    </div>
                </div>

                <div class="ff-hero__wave" aria-hidden="true">
                    <svg viewBox="0 0 1440 48" fill="none" xmlns="http://www.w3.org/2000/svg" class="block w-full">
                        <path d="M0 48V24C240 8 480 0 720 0s480 8 720 24v24H0Z" fill="var(--paper)" />
                    </svg>
                </div>
            </section>

            <div class="ff-body" :class="{ 'ff-body--map': activeMode === 'ngo' }">
              <div class="ff-main">
                <!-- flash -->
                <div v-if="flashSuccess" class="ff-flash ff-flash--ok">{{ flashSuccess }}</div>
                <div v-if="flashError" class="ff-flash ff-flash--err">{{ flashError }}</div>

                <!-- NGO composer (admins only) -->
                <div v-if="feedMeta.can_post_as_ngo" class="ff-composer">
                    <div class="ff-composer__top">
                        <div>
                            <h2 class="ff-composer__title">Post as {{ feedMeta.ngo_name || 'your NGO' }}</h2>
                            <p class="ff-composer__hint">Same form lives in your workspace under <strong>Post an update</strong>.</p>
                        </div>
                        <div class="ff-composer__links">
                            <Link href="/ngo/post-update" class="ff-btn ff-btn--gold ff-btn--sm">Simple post page</Link>
                            <Link :href="feedMeta.social_connect_url" class="ff-btn ff-btn--ghost ff-btn--sm">Auto-share setup</Link>
                        </div>
                    </div>
                    <p v-if="feedMeta.social_connected_platforms?.length" class="ff-composer__note">
                        Auto-share after each post:
                        <strong>{{ formatPlatforms(feedMeta.social_connected_platforms) }}</strong>
                    </p>
                    <form class="ff-composer__form" @submit.prevent="submitNgoPost">
                        <input
                            v-model="feedForm.title"
                            type="text"
                            maxlength="200"
                            required
                            placeholder="Headline"
                            class="ff-field"
                        >
                        <textarea
                            v-model="feedForm.body"
                            required
                            rows="4"
                            maxlength="8000"
                            placeholder="Update your supporters…"
                            class="ff-field"
                        />
                        <div>
                            <p class="ff-composer__sublabel">Photos &amp; videos (optional, up to 12)</p>
                            <label class="ff-upload">
                                Choose files
                                <input
                                    type="file"
                                    multiple
                                    accept="image/jpeg,image/png,image/webp,video/mp4,video/webm,video/quicktime"
                                    class="sr-only"
                                    @change="onFeedMediaPick"
                                >
                            </label>
                            <div v-if="feedSlots.length" class="ff-upload__grid">
                                <div v-for="(s, i) in feedSlots" :key="s.id" class="ff-upload__cell">
                                    <img v-if="s.kind === 'image'" :src="s.url" alt="">
                                    <video v-else :src="s.url" muted playsinline />
                                    <button type="button" class="ff-upload__x" @click="removeFeedSlot(i)">×</button>
                                </div>
                            </div>
                        </div>
                        <p v-if="feedForm.errors.media_files" class="ff-err">{{ feedForm.errors.media_files }}</p>
                        <p v-if="feedForm.errors.title" class="ff-err">{{ feedForm.errors.title }}</p>
                        <p v-if="feedForm.errors.body" class="ff-err">{{ feedForm.errors.body }}</p>
                        <button type="submit" class="ff-btn ff-btn--gold" :disabled="feedForm.processing">
                            {{ feedForm.processing ? 'Publishing…' : 'Publish to live feed' }}
                        </button>
                    </form>
                </div>

                <!-- Search + mode toggle (sticky) -->
                <div class="ff-controls">
                    <div class="ff-search">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        <input
                            v-model="searchText"
                            type="search"
                            autocomplete="off"
                            placeholder="Search posts, NGOs, locations…"
                        >
                    </div>
                    <div class="ff-modes">
                        <button type="button" class="ff-mode" :class="{ 'ff-mode--on': activeMode === 'feed' }" @click="activeMode = 'feed'">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h8m-8 4h6M5 4h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V6a2 2 0 012-2z" /></svg>
                            Feed
                        </button>
                        <button type="button" class="ff-mode" :class="{ 'ff-mode--on': activeMode === 'ngo' }" @click="activateNgoMode">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0L6.343 16.657a8 8 0 1111.314 0z" /><circle cx="12" cy="11" r="2.5" /></svg>
                            Map
                        </button>
                    </div>
                </div>

                <!-- ============================ FEED STREAM ============================ -->
                <section v-if="activeMode === 'feed'" class="ff-stream" aria-labelledby="feeds-stream-heading">
                    <div
                        v-if="filteredPosts.length === 0"
                        class="ff-empty"
                    >
                        <div class="ff-empty__icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2M7 16h6M7 8h6v4H7V8z" /></svg>
                        </div>
                        <p class="ff-empty__title">No posts match your search</p>
                        <p class="ff-empty__text">Clear the search box, or check back as organisations publish updates.</p>
                    </div>

                    <article
                        v-for="post in filteredPosts"
                        :key="post.id"
                        v-reveal
                        :data-feed-post="String(post.id)"
                        class="ff-post"
                        :style="{ '--accent': postAccent(post) }"
                    >
                        <header class="ff-post__head">
                            <component
                                :is="post.ngo?.slug ? Link : 'span'"
                                :href="post.ngo?.slug ? `/ngos/${post.ngo.slug}` : undefined"
                                class="ff-post__id"
                                :class="{ 'ff-post__id--link': post.ngo?.slug }"
                            >
                                <span class="ff-post__avatar">
                                    <img v-if="post.ngo?.logo" :src="post.ngo.logo" :alt="post.ngo?.name" @error="handleImageError">
                                    <span v-else>{{ (post.ngo?.name || post.author?.name || 'F').charAt(0).toUpperCase() }}</span>
                                </span>
                                <span class="ff-post__idmeta">
                                    <span class="ff-post__author">{{ post.ngo?.name || post.author?.name }}</span>
                                    <time class="ff-post__time" :datetime="post.created_at" :title="formatTime(post.created_at)">{{ timeAgo(post.created_at) }}</time>
                                </span>
                            </component>
                            <span v-if="post.ngo" class="ff-post__badge" title="Verified organisation">
                                <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.7-9.3a1 1 0 00-1.4-1.4L9 10.6 7.7 9.3a1 1 0 00-1.4 1.4l2 2a1 1 0 001.4 0l4-4z" clip-rule="evenodd" /></svg>
                            </span>
                        </header>

                        <Link :href="post.public_url || `/feeds/${post.id}`" class="ff-post__titlelink">
                            <h2 class="ff-display ff-post__title">{{ post.title }}</h2>
                        </Link>
                        <p class="ff-post__text">{{ post.body }}</p>

                        <div v-if="post.media?.length" class="ff-post__media" :class="post.media.length > 1 ? 'ff-post__media--grid' : ''">
                            <template v-for="(item, idx) in post.media" :key="idx">
                                <img
                                    v-if="item.type === 'image'"
                                    :src="resolveMedia(item.path)"
                                    :alt="`${post.title} ${idx + 1}`"
                                    loading="lazy"
                                    @error="handleImageError"
                                >
                                <video v-else :src="resolveMedia(item.path)" controls playsinline preload="metadata" />
                            </template>
                        </div>
                        <div v-else-if="post.image_url" class="ff-post__media">
                            <img :src="resolveImage(post.image_url)" :alt="post.title" loading="lazy" @error="handleImageError">
                        </div>

                        <div class="ff-post__metrics">
                            <span class="ff-post__react-sum">
                                <span class="ff-react-tally"><span class="ff-react-tally__e">👍</span><b v-bump="post.reactions.totals.like" class="ff-num">{{ post.reactions.totals.like }}</b></span>
                                <span class="ff-react-tally"><span class="ff-react-tally__e">❤️</span><b v-bump="post.reactions.totals.love" class="ff-num">{{ post.reactions.totals.love }}</b></span>
                                <span class="ff-react-tally"><span class="ff-react-tally__e">🤝</span><b v-bump="post.reactions.totals.support" class="ff-num">{{ post.reactions.totals.support }}</b></span>
                            </span>
                            <span class="ff-post__chips">
                                <span class="ff-metric" title="Views">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.5 12S5.5 5.5 12 5.5 21.5 12 21.5 12 18.5 18.5 12 18.5 2.5 12 2.5 12z" /><circle cx="12" cy="12" r="3" /></svg>
                                    <b v-bump="post.views_count ?? 0" class="ff-num">{{ post.views_count ?? 0 }}</b>
                                </span>
                                <span class="ff-metric" title="Comments">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z" /></svg>
                                    <b v-bump="post.comments.length" class="ff-num">{{ post.comments.length }}</b>
                                </span>
                                <span class="ff-metric" title="Shares">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3" /><circle cx="6" cy="12" r="3" /><circle cx="18" cy="19" r="3" /><path stroke-linecap="round" stroke-linejoin="round" d="M8.6 13.5l6.8 4M15.4 6.5l-6.8 4" /></svg>
                                    <b v-bump="post.shares_count" class="ff-num">{{ post.shares_count }}</b>
                                </span>
                            </span>
                        </div>

                        <div class="ff-post__actions">
                            <button type="button" class="ff-react" :class="{ 'ff-react--on ff-react--like': post.reactions.my_reaction === 'like' }" @click="react(post.id, 'like')">
                                <span class="ff-react__emoji">👍</span> Like
                            </button>
                            <button type="button" class="ff-react" :class="{ 'ff-react--on ff-react--love': post.reactions.my_reaction === 'love' }" @click="react(post.id, 'love')">
                                <span class="ff-react__emoji">❤️</span> Love
                            </button>
                            <button type="button" class="ff-react" :class="{ 'ff-react--on ff-react--support': post.reactions.my_reaction === 'support' }" @click="react(post.id, 'support')">
                                <span class="ff-react__emoji">🤝</span> Support
                            </button>
                            <button type="button" class="ff-react ff-react--share" :class="{ 'ff-react--open': shareFor === post.id }" @click="toggleShare(post.id)">
                                <span class="ff-react__emoji">↗</span> Share
                            </button>
                        </div>

                        <transition name="ff-fade">
                            <div v-if="shareFor === post.id" class="ff-share">
                                <button type="button" class="ff-share__chip" @click="shareVia(post, 'link')">Copy link</button>
                                <button type="button" class="ff-share__chip" @click="shareVia(post, 'whatsapp')">WhatsApp</button>
                                <button type="button" class="ff-share__chip" @click="shareVia(post, 'facebook')">Facebook</button>
                                <button type="button" class="ff-share__chip" @click="shareVia(post, 'linkedin')">LinkedIn</button>
                                <button type="button" class="ff-share__chip" @click="shareVia(post, 'instagram')">Instagram caption</button>
                            </div>
                        </transition>

                        <Link v-if="post.ngo?.slug" :href="`/ngos/${post.ngo.slug}`" class="ff-post__impact">
                            <span>View {{ post.ngo.name }}'s impact</span>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6l6 6-6 6" /></svg>
                        </Link>

                        <div class="ff-post__comments">
                            <div v-if="post.comments.length" class="ff-comments">
                                <div v-for="comment in post.comments" :key="comment.id" class="ff-comment">
                                    <span class="ff-comment__avatar">{{ (comment.user_name || 'M').charAt(0).toUpperCase() }}</span>
                                    <div class="ff-comment__bubble">
                                        <p class="ff-comment__name">{{ comment.user_name }}</p>
                                        <p class="ff-comment__text">{{ comment.comment }}</p>
                                    </div>
                                </div>
                            </div>
                            <form class="ff-commentform" @submit.prevent="submitComment(post.id)">
                                <input
                                    v-model="commentDrafts[post.id]"
                                    type="text"
                                    maxlength="500"
                                    placeholder="Write a comment…"
                                    @focus="isGuest && openAuthPrompt('comment on this post')"
                                >
                                <button type="submit" class="ff-commentform__send" aria-label="Post comment">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6l6 6-6 6" /></svg>
                                </button>
                            </form>
                        </div>
                    </article>
                </section>

                <!-- ============================ MAP ============================ -->
                <section v-else class="ff-mapwrap" aria-labelledby="feeds-map-heading">
                    <div class="ff-maphead">
                        <p class="ff-kicker">Geography</p>
                        <h2 id="feeds-map-heading" class="ff-display ff-h2">Verified NGOs on the map</h2>
                        <p class="ff-maphead__sub">District boundaries from Karnataka GeoJSON; markers show NGO locations. Use your position to sort by distance.</p>
                    </div>

                    <div class="ff-mapfilters">
                        <button type="button" class="ff-btn ff-btn--ghost ff-btn--sm" @click="useMyLocation">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:1em;height:1em"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0L6.343 16.657a8 8 0 1111.314 0z" /><circle cx="12" cy="11" r="2.5" /></svg>
                            Use my location
                        </button>
                        <select v-model.number="distanceKm" class="ff-select">
                            <option :value="10">Within 10 km</option>
                            <option :value="25">Within 25 km</option>
                            <option :value="50">Within 50 km</option>
                            <option :value="100">Within 100 km</option>
                            <option :value="1000">All Karnataka</option>
                        </select>
                        <select v-model="selectedFocus" class="ff-select">
                            <option value="">All focus areas</option>
                            <option v-for="focus in focusOptions" :key="focus" :value="focus">{{ focus }}</option>
                        </select>
                    </div>
                    <p class="ff-mapcount">
                        Showing <strong>{{ filteredNgos.length }}</strong> NGOs for your filters
                        <span v-if="mapLocationDistrictName" class="ff-mapcount__hi">
                            · Highlight: <strong>{{ mapLocationDistrictName }}</strong>
                            <span v-if="userLocation"> (your location)</span>
                            <span v-else-if="filteredNgos.length"> (first listed)</span>
                        </span>
                        <span v-if="mapGeoError" class="ff-mapcount__warn"> · Boundary layer unavailable (map still works)</span>
                    </p>

                    <div class="feeds-map-shell relative isolate overflow-hidden">
                        <div id="ngo-map" class="feeds-map-canvas h-[min(52vh,440px)] min-h-[320px] sm:min-h-[380px]" />
                        <div
                            v-show="mapGeoLoading"
                            class="feeds-map-loader pointer-events-none absolute inset-0 flex flex-col items-center justify-center gap-3"
                            role="status"
                            aria-live="polite"
                            :aria-busy="mapGeoLoading"
                        >
                            <div class="feeds-map-spinner h-10 w-10 rounded-full" />
                            <p class="text-sm font-semibold" style="color:var(--ink)">Loading map layers</p>
                            <p class="max-w-xs px-4 text-center text-xs" style="color:#6a6e7a">Karnataka district boundaries (GeoJSON)…</p>
                        </div>
                    </div>

                    <div class="ff-ngogrid">
                        <article v-for="ngo in filteredNgos" :key="ngo.id" class="ff-ngocard">
                            <div class="ff-ngocard__top">
                                <img :src="ngo.logo || '/assets/images/fevourd-k/logo.png'" :alt="ngo.name" @error="handleImageError">
                                <div>
                                    <p class="ff-ngocard__name">{{ ngo.name }}</p>
                                    <p class="ff-ngocard__dist">
                                        {{ ngo.distance_km !== null ? `${ngo.distance_km.toFixed(1)} km away` : `Lat ${ngo.latitude.toFixed(3)}, Lng ${ngo.longitude.toFixed(3)}` }}
                                    </p>
                                </div>
                            </div>
                            <p class="ff-ngocard__desc">{{ ngo.description || 'Verified NGO impact profile.' }}</p>
                            <div class="ff-ngocard__tags">
                                <span v-for="focus in (ngo.focus_areas || []).slice(0, 3)" :key="focus">{{ focus }}</span>
                            </div>
                        </article>
                    </div>
                </section>
              </div>

              <!-- ============================ DESKTOP DISCOVERY SIDEBAR ============================ -->
              <aside class="ff-side" aria-label="Discover organisations">
                  <div class="ff-side__card">
                      <div class="ff-side__head">
                          <p class="ff-side__kicker">The federation</p>
                          <h2 class="ff-display ff-side__title">Browse verified NGOs</h2>
                          <p class="ff-side__sub">{{ ngoCount }} organisations mapped across Karnataka. Open any profile for live impact.</p>
                      </div>
                      <ul class="ff-side__list">
                          <li v-for="ngo in sidebarNgos" :key="ngo.id">
                              <component
                                  :is="ngo.slug ? Link : 'div'"
                                  :href="ngo.slug ? `/ngos/${ngo.slug}` : undefined"
                                  class="ff-side__ngo"
                              >
                                  <span class="ff-side__avatar">
                                      <img :src="ngo.logo || brandLogoSrc" :alt="ngo.name" @error="handleImageError">
                                  </span>
                                  <span class="ff-side__meta">
                                      <span class="ff-side__name">{{ ngo.name }}</span>
                                      <span class="ff-side__focus">{{ (ngo.focus_areas || [])[0] || 'Verified organisation' }}</span>
                                  </span>
                                  <svg class="ff-side__arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 6l6 6-6 6" /></svg>
                              </component>
                          </li>
                      </ul>
                      <div class="ff-side__actions">
                          <button type="button" class="ff-btn ff-btn--gold ff-btn--sm" @click="activateNgoMode">
                              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:1em;height:1em"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0L6.343 16.657a8 8 0 1111.314 0z" /><circle cx="12" cy="11" r="2.5" /></svg>
                              Open the map
                          </button>
                          <Link href="/ngos" class="ff-btn ff-btn--ghost ff-btn--sm">All organisations</Link>
                      </div>
                  </div>

                  <div class="ff-side__stats">
                      <div class="ff-side__stat">
                          <span class="ff-display ff-side__statnum">{{ postCount }}</span>
                          <span class="ff-side__statlbl">Posts live</span>
                      </div>
                      <div class="ff-side__stat">
                          <span class="ff-display ff-side__statnum ff-side__statnum--gold">{{ ngoCount }}</span>
                          <span class="ff-side__statlbl">Verified NGOs</span>
                      </div>
                  </div>
              </aside>
            </div>

            <!-- ============================ LOGIN PROMPT (guest actions) ============================ -->
            <transition name="ff-modal">
                <div v-if="authPrompt.open" class="ff-authwrap" @click.self="closeAuthPrompt">
                    <div class="ff-auth" role="dialog" aria-modal="true" aria-labelledby="ff-auth-title">
                        <button class="ff-auth__x" @click="closeAuthPrompt" aria-label="Close">✕</button>
                        <div class="ff-auth__emblem"><img :src="brandLogoSrc" alt="" width="52" height="52"></div>
                        <h3 id="ff-auth-title" class="ff-display ff-auth__title">Join the federation</h3>
                        <p class="ff-auth__text">
                            Sign in to <strong>{{ authPrompt.action }}</strong> and stand with
                            800+ voluntary organisations across Karnataka.
                        </p>
                        <div class="ff-auth__btns">
                            <Link href="/login" class="ff-btn ff-btn--gold">Log in</Link>
                            <Link href="/register" class="ff-btn ff-btn--ghost-dark">Create account</Link>
                        </div>
                        <button class="ff-auth__later" @click="closeAuthPrompt">Maybe later</button>
                    </div>
                </div>
            </transition>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link, router, useForm, usePage } from '@inertiajs/vue3'
import { computed, nextTick, onBeforeUnmount, onMounted, reactive, ref, watch } from 'vue'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'

const page = usePage()
const brandLogoSrc = '/assets/images/fevourd-k/logo.png'

const props = defineProps({
    posts: { type: Array, default: () => [] },
    ngos: { type: Array, default: () => [] },
    feedMeta: {
        type: Object,
        default: () => ({
            can_post_as_ngo: false,
            ngo_name: null,
            social_connected_platforms: [],
            social_connect_url: '/ngo/social-connect',
        }),
    },
})

const flashSuccess = computed(() => page.props.flash?.success ?? null)
const flashError = computed(() => page.props.flash?.error ?? null)

/* ---- guest gating: prompt sign-in before member actions ---- */
const isGuest = computed(() => !page.props.auth?.user)
const authPrompt = ref({ open: false, action: '' })
const openAuthPrompt = (action) => { authPrompt.value = { open: true, action } }
const closeAuthPrompt = () => { authPrompt.value.open = false }
const requireAuth = (action, fn) => {
    if (isGuest.value) { openAuthPrompt(action); return false }
    fn?.()
    return true
}

const feedForm = useForm({
    title: '',
    body: '',
    media_files: [],
})

const shareFor = ref(null)
const feedSlots = ref([])
let feedSlotSeq = 0
const viewObserver = ref(null)
const observedFeedEls = new WeakSet()

const getKarnatakaGeoUrl = () => {
    if (typeof window !== 'undefined') {
        return new URL('/assets/Themes/karnataka.geojson', window.location.origin).href
    }

    return '/assets/Themes/karnataka.geojson'
}

const districtKeyFromProps = (props) => String(props?.dt_code ?? props?.district ?? '')

const pointInRingLngLat = (lng, lat, ring) => {
    let inside = false
    for (let i = 0, j = ring.length - 1; i < ring.length; j = i++) {
        const xi = ring[i][0]
        const yi = ring[i][1]
        const xj = ring[j][0]
        const yj = ring[j][1]
        if ((yi > lat) !== (yj > lat) && lng < ((xj - xi) * (lat - yi)) / (yj - yi) + xi) {
            inside = !inside
        }
    }

    return inside
}

const pointInPolygonLngLat = (lng, lat, geometry) => {
    if (!geometry) return false
    if (geometry.type === 'Polygon') {
        const coords = geometry.coordinates
        if (!coords?.[0]?.length) return false
        if (!pointInRingLngLat(lng, lat, coords[0])) return false
        for (let h = 1; h < coords.length; h++) {
            if (pointInRingLngLat(lng, lat, coords[h])) return false
        }

        return true
    }
    if (geometry.type === 'MultiPolygon') {
        for (const poly of geometry.coordinates || []) {
            if (!poly?.[0]?.length) continue
            if (!pointInRingLngLat(lng, lat, poly[0])) continue
            let inHole = false
            for (let h = 1; h < poly.length; h++) {
                if (pointInRingLngLat(lng, lat, poly[h])) {
                    inHole = true
                    break
                }
            }
            if (!inHole) return true
        }

        return false
    }

    return false
}

const findDistrictKeyAt = (featureCollection, lat, lng) => {
    const lngN = Number(lng)
    const latN = Number(lat)
    for (const f of featureCollection.features || []) {
        if (pointInPolygonLngLat(lngN, latN, f.geometry)) {
            return districtKeyFromProps(f.properties)
        }
    }

    return null
}

const getDistrictPolygonStyle = (feature, highlightedKey) => {
    const key = districtKeyFromProps(feature.properties)
    const isHi = Boolean(highlightedKey && key && key === highlightedKey)

    return {
        color: isHi ? '#0d1f5c' : '#1d4ed8',
        weight: isHi ? 3 : 1.5,
        fillColor: isHi ? '#f2b40c' : '#93c5fd',
        fillOpacity: isHi ? 0.32 : 0.18,
    }
}

const activeMode = ref('feed')
const searchText = ref('')
const commentDrafts = reactive({})
const userLocation = ref(null)
const distanceKm = ref(50)
const selectedFocus = ref('')
const mapGeoLoading = ref(false)
const mapGeoError = ref(false)
const highlightedDistrictKey = ref(null)
const mapLocationDistrictName = ref(null)

let map = null
let markersLayer = null
let geoJsonLayer = null
let karnatakaGeoCache = null

const postCount = computed(() => props.posts.length)
const ngoCount = computed(() => props.ngos.length)

/* desktop discovery sidebar: a handful of verified NGOs to deep-link into */
const sidebarNgos = computed(() => props.ngos.slice(0, 6))

/* ---- local reactive mirror of posts so counts can update without mutating the prop ---- */
const clonePost = (p) => ({
    ...p,
    reactions: {
        totals: { ...(p.reactions?.totals ?? {}) },
        my_reaction: p.reactions?.my_reaction ?? null,
    },
})
const localPosts = ref(props.posts.map(clonePost))
watch(
    () => props.posts,
    (next) => { localPosts.value = (next || []).map(clonePost) },
    { deep: true },
)

const filteredPosts = computed(() => {
    if (!searchText.value.trim()) {
        return localPosts.value
    }
    const q = searchText.value.toLowerCase()
    return localPosts.value.filter((post) =>
        post.title?.toLowerCase().includes(q) ||
        post.body?.toLowerCase().includes(q) ||
        post.ngo?.name?.toLowerCase().includes(q)
    )
})

/* subtle teal→blue accent hue derived from the post id (design-language per-id hue) */
const postAccent = (post) => `hsl(${175 + (Number(post.id) * 47) % 70} 64% 42%)`

/* directive: gentle number bump whenever a displayed count changes */
const vBump = {
    updated(el, binding) {
        if (binding.value === binding.oldValue) return
        el.classList.remove('ff-num--bump')
        void el.offsetWidth // restart the animation
        el.classList.add('ff-num--bump')
    },
}

/* directive: reveal-on-scroll entrance, respecting reduced-motion */
const vReveal = {
    mounted(el) {
        const reduce = window.matchMedia?.('(prefers-reduced-motion: reduce)')?.matches
        if (reduce || typeof IntersectionObserver === 'undefined') {
            el.classList.add('is-revealed')
            return
        }
        el.classList.add('ff-reveal')
        const io = new IntersectionObserver((entries, obs) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    el.classList.add('is-revealed')
                    obs.unobserve(entry.target)
                }
            })
        }, { threshold: 0.08 })
        io.observe(el)
        el._revealObs = io
    },
    unmounted(el) { el._revealObs?.disconnect() },
}

const ngosWithDistance = computed(() => {
    return props.ngos.map((ngo) => {
        const distance = userLocation.value
            ? haversineKm(userLocation.value.lat, userLocation.value.lng, ngo.latitude, ngo.longitude)
            : null
        return { ...ngo, distance_km: distance }
    })
})

const focusOptions = computed(() => {
    const set = new Set()
    props.ngos.forEach((ngo) => (ngo.focus_areas || []).forEach((focus) => set.add(focus)))
    return Array.from(set).sort((a, b) => a.localeCompare(b))
})

const filteredNgos = computed(() => {
    const q = searchText.value.trim().toLowerCase()

    return ngosWithDistance.value.filter((ngo) => {
        if (q && !(
            ngo.name?.toLowerCase().includes(q) ||
            ngo.description?.toLowerCase().includes(q)
        )) {
            return false
        }
        if (selectedFocus.value && !(ngo.focus_areas || []).includes(selectedFocus.value)) {
            return false
        }
        if (ngo.distance_km !== null && ngo.distance_km > distanceKm.value) {
            return false
        }
        return true
    }).sort((a, b) => {
        if (a.distance_km === null && b.distance_km === null) return 0
        if (a.distance_km === null) return 1
        if (b.distance_km === null) return -1
        return a.distance_km - b.distance_km
    })
})

const mapHighlightAnchor = computed(() => {
    if (userLocation.value?.lat != null && userLocation.value?.lng != null) {
        return { lat: userLocation.value.lat, lng: userLocation.value.lng }
    }
    const withCoords = filteredNgos.value.filter((n) => n.latitude != null && n.longitude != null)
    if (withCoords.length) {
        return { lat: withCoords[0].latitude, lng: withCoords[0].longitude }
    }

    return { lat: 15.3173, lng: 75.7139 }
})

const applyReaction = (postId, type) => {
    const post = localPosts.value.find((p) => p.id === postId)
    if (!post) return
    const totals = post.reactions.totals
    const prev = post.reactions.my_reaction
    // Mirror the server: react() does updateOrCreate keyed on (post, user), so
    // repeating the same reaction is a no-op and a different one switches it.
    if (prev === type) return
    if (prev && totals[prev] != null) {
        totals[prev] = Math.max(0, totals[prev] - 1)
    }
    totals[type] = (totals[type] ?? 0) + 1
    post.reactions.my_reaction = type
}

const bumpViews = (postId) => {
    const post = localPosts.value.find((p) => String(p.id) === String(postId))
    if (post) {
        post.views_count = (post.views_count ?? 0) + 1
    }
}

const postReaction = (postId, type) => {
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
    fetch(`/feeds/${postId}/react`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            Accept: 'application/json',
            'X-CSRF-TOKEN': token || '',
            'X-Requested-With': 'XMLHttpRequest',
        },
        credentials: 'same-origin',
        body: JSON.stringify({ type }),
    }).catch(() => {})
}

const react = (postId, type) => {
    requireAuth('react to this post', () => {
        // Optimistic UI is authoritative; the write is sent without a full
        // Inertia reload so overlapping taps can't clobber newer local state.
        applyReaction(postId, type)
        postReaction(postId, type)
    })
}

const submitComment = (postId) => {
    const comment = commentDrafts[postId]?.trim()
    if (!comment) {
        if (isGuest.value) openAuthPrompt('comment on this post')
        return
    }
    if (!requireAuth('comment on this post')) return

    router.post(`/feeds/${postId}/comment`, { comment }, {
        preserveScroll: true,
        onSuccess: () => { commentDrafts[postId] = '' },
    })
}

const shareText = (post) => `${post.title} - ${post.body?.slice(0, 120) || ''}`.trim()

const toggleShare = (postId) => {
    shareFor.value = shareFor.value === postId ? null : postId
}

const formatPlatforms = (list) => {
    const labels = { facebook: 'Facebook', instagram: 'Instagram', linkedin: 'LinkedIn', google_business: 'Google Business' }
    return (list || []).map((p) => labels[p] || p).join(', ')
}

const onFeedMediaPick = (e) => {
    const files = Array.from(e.target.files || [])
    e.target.value = ''
    const allowedVideo = ['video/mp4', 'video/webm', 'video/quicktime']
    for (const f of files) {
        if (feedSlots.value.length >= 12) {
            break
        }
        let kind = null
        if (/^image\/(jpeg|jpg|png|webp)$/i.test(f.type)) {
            kind = 'image'
        } else if (allowedVideo.includes(f.type)) {
            kind = 'video'
        }
        if (!kind) {
            continue
        }
        feedSlots.value.push({
            id: ++feedSlotSeq,
            file: f,
            url: URL.createObjectURL(f),
            kind,
        })
    }
}

const removeFeedSlot = (i) => {
    const s = feedSlots.value[i]
    if (s?.url) {
        URL.revokeObjectURL(s.url)
    }
    feedSlots.value.splice(i, 1)
}

const clearFeedSlots = () => {
    feedSlots.value.forEach((s) => URL.revokeObjectURL(s.url))
    feedSlots.value = []
}

const submitNgoPost = () => {
    feedForm.media_files = feedSlots.value.map((s) => s.file)
    feedForm.post('/feeds', {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            feedForm.reset()
            feedForm.clearErrors()
            clearFeedSlots()
        },
    })
}

const shareVia = async (post, channel) => {
    const shareUrl = post.public_url || `${window.location.origin}/feeds/${post.id}`
    const text = shareText(post)
    const full = `${text} ${shareUrl}`.trim()

    if (channel === 'link') {
        try {
            if (navigator.share) {
                await navigator.share({ title: post.title, text, url: shareUrl })
            } else {
                await navigator.clipboard.writeText(full)
            }
        } catch {
            try {
                await navigator.clipboard.writeText(full)
            } catch {
                /* ignore */
            }
        }
    } else if (channel === 'whatsapp') {
        window.open(`https://wa.me/?text=${encodeURIComponent(full)}`, '_blank', 'noopener,noreferrer')
    } else if (channel === 'facebook') {
        window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(shareUrl)}`, '_blank', 'noopener,noreferrer')
    } else if (channel === 'linkedin') {
        window.open(`https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(shareUrl)}`, '_blank', 'noopener,noreferrer')
    } else if (channel === 'instagram') {
        try {
            await navigator.clipboard.writeText(full)
        } catch {
            /* ignore */
        }
    }

    // Sharing itself works for everyone; only record the share when signed in.
    if (!isGuest.value) {
        router.post(`/feeds/${post.id}/share`, { channel }, { preserveScroll: true })
    }
    shareFor.value = null
}

const formatTime = (isoDate) => new Date(isoDate).toLocaleString()

const timeAgo = (iso) => {
    if (!iso) return ''
    const d = (Date.now() - new Date(iso).getTime()) / 1000
    if (d < 60) return 'just now'
    if (d < 3600) return Math.round(d / 60) + 'm ago'
    if (d < 86400) return Math.round(d / 3600) + 'h ago'
    if (d < 604800) return Math.round(d / 86400) + 'd ago'
    return new Date(iso).toLocaleDateString('en-IN', { day: 'numeric', month: 'short', year: 'numeric' })
}

const resolveImage = (url) => url || '/assets/images/fevourd-k/logo.png'

const resolveMedia = (path) => {
    if (!path) {
        return '/assets/images/fevourd-k/logo.png'
    }
    if (path.startsWith('http')) {
        return path
    }
    return path.startsWith('/') ? path : `/${path}`
}

const handleImageError = (event) => {
    event.target.src = '/assets/images/fevourd-k/logo.png'
}

const bindFeedViewTracking = () => {
    if (!viewObserver.value) {
        return
    }
    document.querySelectorAll('[data-feed-post]').forEach((el) => {
        if (observedFeedEls.has(el)) {
            return
        }
        observedFeedEls.add(el)
        viewObserver.value.observe(el)
    })
}

const setupFeedViewObserver = () => {
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
    const seenIds = new Set()
    viewObserver.value = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return
                }
                const id = entry.target.getAttribute('data-feed-post')
                if (!id || seenIds.has(id)) {
                    return
                }
                seenIds.add(id)
                bumpViews(id) // optimistic, once per post per session
                fetch(`/feeds/${id}/view`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': token || '',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'same-origin',
                    body: '{}',
                }).catch(() => {})
            })
        },
        { threshold: 0.35, rootMargin: '0px' },
    )
}

const renderMapMarkers = () => {
    if (!map || !markersLayer) return
    markersLayer.clearLayers()
    filteredNgos.value.forEach((ngo) => {
        const marker = L.marker([ngo.latitude, ngo.longitude])
        marker.bindPopup(`<strong>${ngo.name}</strong><br/>${ngo.description || 'Impact NGO'}`)
        marker.addTo(markersLayer)
    })
}

const fitMapToContent = () => {
    if (!map) return
    const bounds = L.latLngBounds([])
    if (geoJsonLayer) {
        bounds.extend(geoJsonLayer.getBounds())
    }
    filteredNgos.value.forEach((ngo) => {
        if (ngo.latitude != null && ngo.longitude != null) {
            bounds.extend([ngo.latitude, ngo.longitude])
        }
    })
    if (bounds.isValid()) {
        map.fitBounds(bounds, { padding: [48, 48], maxZoom: 10 })
    }
}

const updateDistrictHighlight = () => {
    if (!geoJsonLayer || !karnatakaGeoCache) return
    const { lat, lng } = mapHighlightAnchor.value
    const hKey = findDistrictKeyAt(karnatakaGeoCache, lat, lng)
    highlightedDistrictKey.value = hKey
    const hit = karnatakaGeoCache.features.find((x) => districtKeyFromProps(x.properties) === hKey)
    mapLocationDistrictName.value = hit?.properties?.district || null
    geoJsonLayer.eachLayer((layer) => {
        if (layer.feature) {
            layer.setStyle(getDistrictPolygonStyle(layer.feature, hKey))
        }
    })
}

const loadKarnatakaBoundaryIntoMap = async () => {
    if (!map || geoJsonLayer) return

    const minLoaderMs = 550
    const started = typeof performance !== 'undefined' ? performance.now() : Date.now()

    mapGeoLoading.value = true
    mapGeoError.value = false
    await nextTick()
    await new Promise((resolve) => {
        requestAnimationFrame(() => requestAnimationFrame(() => resolve()))
    })

    try {
        const geoUrl = getKarnatakaGeoUrl()
        if (!karnatakaGeoCache) {
            const res = await fetch(geoUrl, { credentials: 'same-origin' })
            if (!res.ok) {
                throw new Error('geo fetch failed')
            }
            karnatakaGeoCache = await res.json()
        }
        geoJsonLayer = L.geoJSON(karnatakaGeoCache, {
            style: (feature) => getDistrictPolygonStyle(feature, null),
            onEachFeature: (feature, layer) => {
                const name = feature.properties?.district
                if (name) {
                    layer.bindTooltip(name, { sticky: true, className: 'feeds-map-dist-tooltip' })
                }
            },
        })
        geoJsonLayer.addTo(map)
        if (typeof geoJsonLayer.bringToBack === 'function') {
            geoJsonLayer.bringToBack()
        }
        updateDistrictHighlight()
    } catch {
        mapGeoError.value = true
    } finally {
        const now = typeof performance !== 'undefined' ? performance.now() : Date.now()
        const elapsed = now - started
        if (elapsed < minLoaderMs) {
            await new Promise((r) => setTimeout(r, minLoaderMs - elapsed))
        }
        mapGeoLoading.value = false
    }
}

const ensureMap = async () => {
    await nextTick()
    const mapElement = document.getElementById('ngo-map')
    if (!mapElement) return

    if (map) {
        map.invalidateSize()
        renderMapMarkers()
        updateDistrictHighlight()
        fitMapToContent()

        return
    }

    map = L.map('ngo-map', { scrollWheelZoom: true }).setView([15.3173, 75.7139], 7)
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; OpenStreetMap &copy; CARTO',
        subdomains: 'abcd',
        maxZoom: 20,
    }).addTo(map)

    await loadKarnatakaBoundaryIntoMap()

    if (!markersLayer) {
        markersLayer = L.layerGroup().addTo(map)
    }
    renderMapMarkers()
    fitMapToContent()
}

const activateNgoMode = async () => {
    activeMode.value = 'ngo'
    await ensureMap()
}

const useMyLocation = () => {
    if (!navigator.geolocation) return
    navigator.geolocation.getCurrentPosition(
        async (position) => {
            userLocation.value = {
                lat: position.coords.latitude,
                lng: position.coords.longitude,
            }
            await ensureMap()
        },
        () => {},
        { enableHighAccuracy: true, timeout: 8000, maximumAge: 0 }
    )
}

const haversineKm = (lat1, lon1, lat2, lon2) => {
    const toRad = (deg) => (deg * Math.PI) / 180
    const R = 6371
    const dLat = toRad(lat2 - lat1)
    const dLon = toRad(lon2 - lon1)
    const a =
        Math.sin(dLat / 2) ** 2 +
        Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) * Math.sin(dLon / 2) ** 2
    return 2 * R * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a))
}

watch([searchText, selectedFocus, distanceKm, userLocation, activeMode], async () => {
    if (activeMode.value === 'ngo') {
        await ensureMap()
    }
})

watch(
    [userLocation, filteredNgos],
    async () => {
        if (activeMode.value !== 'ngo' || !geoJsonLayer) return
        await nextTick()
        updateDistrictHighlight()
    },
    { deep: true },
)

watch(
    () => props.posts,
    async () => {
        await nextTick()
        bindFeedViewTracking()
    },
    { deep: true },
)

onBeforeUnmount(() => {
    viewObserver.value?.disconnect()
    if (map) {
        map.remove()
        map = null
        markersLayer = null
        geoJsonLayer = null
    }
    mapLocationDistrictName.value = null
    highlightedDistrictKey.value = null
})

onMounted(async () => {
    setupFeedViewObserver()
    await nextTick()
    bindFeedViewTracking()
    if (new URLSearchParams(window.location.search).get('view') === 'ngo') {
        await activateNgoMode()
    }
})
</script>

<style scoped>
/* ============ TOKENS ============ */
.ff {
    --ink: #0d1f5c;
    --ink-2: #11286e;
    --ink-deep: #081640;
    --gold: #f2b40c;
    --gold-soft: #f7c948;
    --magenta: #c12a63;
    --emerald: #1f8a5b;
    --paper: #f7f0df;
    --paper-2: #efe6cd;
    --char: #1c2230;
    --font-display: 'Fraunces', 'Playfair Display', Georgia, serif;
    color: var(--char);
    background: var(--paper);
    min-height: 100vh;
}
.ff-display { font-family: var(--font-display); font-optical-sizing: auto; }

.ff-grain {
    position: absolute; inset: 0; pointer-events: none; opacity: .5; mix-blend-mode: overlay;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='2' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.5'/%3E%3C/svg%3E");
}

/* ============ HERO ============ */
.ff-hero {
    position: relative; isolation: isolate; overflow: hidden;
    background: radial-gradient(120% 120% at 85% -10%, #1b3aa0 0%, var(--ink-2) 38%, var(--ink-deep) 100%);
    color: #f4eeda;
    padding: clamp(40px, 9vw, 96px) 0 0;
}
.ff-hero__glow { position: absolute; z-index: -1; width: 60vw; height: 60vw; right: -12vw; top: -16vw;
    background: radial-gradient(circle, rgba(242,180,12,.28), rgba(242,180,12,0) 62%); filter: blur(8px); }
.ff-hero__rings { position: absolute; z-index: -1; right: -10vw; top: 40%; transform: translateY(-50%); width: min(46vw, 460px); aspect-ratio: 1; opacity: .5; }
.ff-ring { position: absolute; inset: 0; border-radius: 50%; border: 1.5px dashed rgba(242,180,12,.4); }
.ff-ring--2 { inset: 14%; border-style: solid; border-color: rgba(193,42,99,.35); animation: ff-spin 60s linear infinite; }
@keyframes ff-spin { to { transform: rotate(360deg); } }

.ff-hero__inner { position: relative; max-width: 1120px; margin: 0 auto; padding: 0 clamp(20px, 5vw, 48px) clamp(40px, 6vw, 64px); text-align: center; }
.ff-eyebrow { display: inline-flex; align-items: center; gap: .55em; font-size: .72rem; font-weight: 700; letter-spacing: .16em; text-transform: uppercase; color: var(--gold-soft); }
.ff-eyebrow__dot { width: 7px; height: 7px; border-radius: 50%; background: var(--gold); box-shadow: 0 0 0 4px rgba(242,180,12,.2); }
.ff-hero__title { font-weight: 600; font-size: clamp(2.4rem, 7vw, 4.6rem); line-height: 1; letter-spacing: -.02em; margin: .45rem 0 0; }
.ff-hero__accent { color: var(--gold); font-style: italic; font-weight: 500; }
.ff-hero__lede { max-width: 44ch; margin: 1.1rem auto 0; font-size: clamp(1rem, 1.6vw, 1.18rem); line-height: 1.6; color: #d9d6c6; }

.ff-hero__stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: .6rem; max-width: 460px; margin: clamp(1.8rem, 4vw, 2.6rem) auto 0; }
.ff-chip { border: 1px solid rgba(247,240,223,.16); background: rgba(247,240,223,.07); backdrop-filter: blur(6px); border-radius: 16px; padding: .85rem .4rem; }
.ff-chip__num { display: block; font-size: clamp(1.5rem, 5vw, 2rem); font-weight: 600; line-height: 1; color: #fff; }
.ff-chip__num--gold { color: var(--gold-soft); }
.ff-chip__num--emerald { color: #6fe3ab; }
.ff-chip__lbl { display: block; margin-top: .4rem; font-size: .68rem; font-weight: 600; letter-spacing: .04em; text-transform: uppercase; color: #c7c4b4; }
.ff-hero__wave { line-height: 0; }

/* ============ BODY LAYOUT ============ */
.ff-body { max-width: 680px; margin: 0 auto; padding: clamp(16px, 4vw, 28px) clamp(14px, 4vw, 20px) clamp(40px, 8vw, 64px); }
.ff-main { display: flex; flex-direction: column; gap: clamp(16px, 4vw, 22px); min-width: 0; }
.ff-side { display: none; }

/* desktop: two-column magazine layout (feed + discovery sidebar) */
@media (min-width: 1024px) {
    .ff-body { max-width: 1160px; display: grid; grid-template-columns: minmax(0, 1fr) 340px; gap: 34px; align-items: start; padding-top: 38px; }
    .ff-body--map { grid-template-columns: minmax(0, 1fr); } /* let the map use the full width */
    .ff-body--map .ff-side { display: none; }
    .ff-side { display: flex; flex-direction: column; gap: 18px; position: sticky; top: 84px; }
}

/* ---- discovery sidebar (desktop only) ---- */
.ff-side__card { background: #fffdf6; border: 1px solid rgba(13,31,92,.1); border-radius: 22px; padding: 20px; box-shadow: 0 18px 44px -30px rgba(13,31,92,.45); }
.ff-side__kicker { font-size: .68rem; font-weight: 700; letter-spacing: .16em; text-transform: uppercase; color: var(--magenta); margin: 0 0 .3rem; }
.ff-side__title { font-weight: 600; font-size: 1.3rem; line-height: 1.15; letter-spacing: -.01em; color: var(--ink); margin: 0; }
.ff-side__sub { margin: .5rem 0 0; font-size: .85rem; line-height: 1.5; color: #6a6e7a; }
.ff-side__list { list-style: none; margin: 1rem 0 0; padding: 0; display: flex; flex-direction: column; gap: .35rem; }
.ff-side__ngo { display: flex; align-items: center; gap: .7rem; padding: .55rem; border-radius: 14px; text-decoration: none; color: inherit; transition: background .2s ease; }
.ff-side__ngo:hover { background: var(--paper-2); }
.ff-side__avatar { width: 38px; height: 38px; flex: none; border-radius: 11px; overflow: hidden; background: var(--paper-2); display: grid; place-items: center; }
.ff-side__avatar img { width: 100%; height: 100%; object-fit: cover; }
.ff-side__meta { display: flex; flex-direction: column; min-width: 0; line-height: 1.25; }
.ff-side__name { font-size: .88rem; font-weight: 700; color: var(--ink); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.ff-side__focus { font-size: .74rem; color: #8a8e9a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.ff-side__arrow { width: 16px; height: 16px; flex: none; margin-left: auto; color: #b4b8c2; transition: transform .2s ease, color .2s ease; }
.ff-side__ngo:hover .ff-side__arrow { transform: translateX(3px); color: var(--gold); }
.ff-side__actions { display: flex; gap: .5rem; margin-top: 1rem; }
.ff-side__actions .ff-btn { flex: 1; }
.ff-side__stats { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
.ff-side__stat { background: linear-gradient(135deg, var(--ink), var(--ink-2)); color: #fff; border-radius: 18px; padding: 1rem; text-align: center; box-shadow: 0 14px 30px -20px rgba(13,31,92,.6); }
.ff-side__statnum { display: block; font-size: 1.9rem; font-weight: 600; line-height: 1; }
.ff-side__statnum--gold { color: var(--gold-soft); }
.ff-side__statlbl { display: block; margin-top: .4rem; font-size: .68rem; font-weight: 600; letter-spacing: .04em; text-transform: uppercase; color: #c7c4b4; }

/* flash */
.ff-flash { border-radius: 16px; padding: .8rem 1rem; font-size: .9rem; font-weight: 500; }
.ff-flash--ok { background: #e7f6ee; border: 1px solid #b9e3cc; color: #166041; }
.ff-flash--err { background: #fdeaea; border: 1px solid #f3c0c0; color: #a12626; }

/* ============ BUTTONS ============ */
.ff-btn { display: inline-flex; align-items: center; justify-content: center; gap: .45em; padding: .8em 1.4em; border-radius: 999px; font-weight: 600; font-size: .92rem; text-decoration: none; border: 1px solid transparent; cursor: pointer; transition: transform .2s ease, box-shadow .2s ease, background .2s ease, color .2s ease; }
.ff-btn--sm { padding: .55em 1em; font-size: .8rem; }
.ff-btn:active { transform: scale(.97); }
.ff-btn--gold { background: var(--gold); color: #2a1c00; box-shadow: 0 12px 26px -12px rgba(242,180,12,.7); }
.ff-btn--gold:hover { box-shadow: 0 16px 32px -12px rgba(242,180,12,.85); transform: translateY(-2px); }
.ff-btn--gold:disabled { opacity: .6; transform: none; }
.ff-btn--ghost { background: #fff; color: var(--ink); border-color: rgba(13,31,92,.18); }
.ff-btn--ghost:hover { background: var(--ink); color: var(--paper); }
.ff-btn--ghost-dark { background: transparent; color: var(--ink); border-color: rgba(13,31,92,.25); }
.ff-btn--ghost-dark:hover { background: rgba(13,31,92,.06); }

/* ============ COMPOSER ============ */
.ff-composer { background: #fffdf6; border: 1px solid rgba(13,31,92,.1); border-radius: 22px; padding: clamp(16px,3vw,22px); box-shadow: 0 18px 40px -28px rgba(13,31,92,.4); display: flex; flex-direction: column; gap: .9rem; }
.ff-composer__top { display: flex; flex-wrap: wrap; align-items: flex-start; justify-content: space-between; gap: .8rem; }
.ff-composer__title { font-family: var(--font-display); font-weight: 600; font-size: 1.15rem; color: var(--ink); margin: 0; }
.ff-composer__hint { font-size: .8rem; color: #6a6e7a; margin: .2rem 0 0; }
.ff-composer__links { display: flex; flex-wrap: wrap; gap: .5rem; }
.ff-composer__note { font-size: .78rem; color: #6a6e7a; }
.ff-composer__form { display: flex; flex-direction: column; gap: .7rem; }
.ff-composer__sublabel { font-size: .78rem; font-weight: 600; color: #6a6e7a; margin: 0 0 .35rem; }
.ff-field { width: 100%; border: 1px solid rgba(13,31,92,.18); border-radius: 14px; padding: .7rem .9rem; font: inherit; font-size: .92rem; background: var(--paper); color: var(--char); transition: border-color .2s ease, box-shadow .2s ease; }
.ff-field:focus { outline: none; border-color: var(--gold); box-shadow: 0 0 0 3px rgba(242,180,12,.22); }
.ff-upload { display: flex; align-items: center; justify-content: center; border: 1.5px dashed rgba(13,31,92,.25); border-radius: 14px; padding: .9rem; font-size: .82rem; font-weight: 700; color: var(--ink); cursor: pointer; background: var(--paper); }
.ff-upload:hover { border-color: var(--gold); background: rgba(242,180,12,.06); }
.ff-upload__grid { margin-top: .6rem; display: grid; grid-template-columns: repeat(3, 1fr); gap: .5rem; }
.ff-upload__cell { position: relative; aspect-ratio: 1; border-radius: 12px; overflow: hidden; border: 1px solid rgba(13,31,92,.12); background: var(--paper-2); }
.ff-upload__cell img, .ff-upload__cell video { width: 100%; height: 100%; object-fit: cover; }
.ff-upload__x { position: absolute; top: 4px; right: 4px; width: 20px; height: 20px; border-radius: 50%; border: none; background: rgba(0,0,0,.6); color: #fff; font-size: 13px; line-height: 1; cursor: pointer; }
.ff-err { font-size: .78rem; color: #c0392b; margin: 0; }

/* ============ CONTROLS (search + mode) ============ */
.ff-controls { position: sticky; top: 10px; z-index: 30; display: flex; flex-direction: column; gap: .6rem; background: rgba(247,240,223,.92); backdrop-filter: blur(10px); border: 1px solid rgba(13,31,92,.1); border-radius: 18px; padding: .7rem; box-shadow: 0 10px 28px -18px rgba(13,31,92,.5); }
.ff-search { position: relative; }
.ff-search svg { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 18px; height: 18px; color: #9095a1; }
.ff-search input { width: 100%; border: 1px solid rgba(13,31,92,.16); border-radius: 13px; padding: .72rem .9rem .72rem 2.55rem; font: inherit; font-size: .92rem; background: #fff; color: var(--char); }
.ff-search input:focus { outline: none; border-color: var(--gold); box-shadow: 0 0 0 3px rgba(242,180,12,.22); }
.ff-modes { display: grid; grid-template-columns: 1fr 1fr; gap: .5rem; }
.ff-mode { display: inline-flex; align-items: center; justify-content: center; gap: .45em; border: 1px solid rgba(13,31,92,.16); background: #fff; border-radius: 13px; padding: .6rem; font: inherit; font-weight: 600; font-size: .9rem; color: #4b5563; cursor: pointer; transition: all .2s ease; }
.ff-mode svg { width: 17px; height: 17px; }
.ff-mode:hover { border-color: rgba(13,31,92,.3); }
.ff-mode--on { background: linear-gradient(135deg, var(--ink), var(--ink-2)); color: #fff; border-color: transparent; box-shadow: 0 10px 20px -10px rgba(13,31,92,.7); }

/* ============ EMPTY ============ */
.ff-empty { text-align: center; background: #fffdf6; border: 1px dashed rgba(13,31,92,.2); border-radius: 22px; padding: clamp(36px, 9vw, 64px) 24px; }
.ff-empty__icon { width: 56px; height: 56px; margin: 0 auto 1rem; display: grid; place-items: center; border-radius: 16px; background: var(--paper-2); color: var(--ink); }
.ff-empty__icon svg { width: 28px; height: 28px; }
.ff-empty__title { font-weight: 700; color: var(--ink); margin: 0; }
.ff-empty__text { margin: .5rem 0 0; font-size: .9rem; color: #6a6e7a; }

/* ============ STREAM ============ */
.ff-stream { display: flex; flex-direction: column; gap: clamp(14px, 3vw, 18px); }

/* ============ POST CARD ============ */
.ff-post { position: relative; background: #fffdf6; border: 1px solid rgba(13,31,92,.1); border-radius: 22px; overflow: hidden; padding: clamp(16px, 3.5vw, 22px); box-shadow: 0 18px 44px -30px rgba(13,31,92,.45); transition: transform .4s cubic-bezier(.2,.7,.2,1), box-shadow .4s ease; }
.ff-post::before { content: ''; position: absolute; inset: 0 0 auto 0; height: 3px; background: linear-gradient(90deg, var(--accent, var(--gold)), transparent 78%); opacity: .65; pointer-events: none; }
.ff-post:hover { transform: translateY(-3px); box-shadow: 0 26px 54px -30px rgba(13,31,92,.5); }

/* reveal-on-scroll entrance */
.ff-reveal { opacity: 0; transform: translateY(14px); }
.ff-reveal.is-revealed { opacity: 1; transform: none; transition: opacity .5s ease, transform .55s cubic-bezier(.2,.7,.2,1); }

/* animated tabular counts */
.ff-num { font-variant-numeric: tabular-nums; display: inline-block; }
.ff-num--bump { animation: ff-bump .4s cubic-bezier(.2,.8,.2,1); }
@keyframes ff-bump { 0% { transform: scale(1); } 35% { transform: scale(1.3); color: var(--accent, var(--gold)); } 100% { transform: scale(1); } }
.ff-post__head { display: flex; align-items: center; gap: .7rem; margin-bottom: .85rem; }
.ff-post__id { display: flex; align-items: center; gap: .7rem; min-width: 0; flex: 1; text-decoration: none; color: inherit; border-radius: 14px; }
.ff-post__id--link { transition: opacity .2s ease; }
.ff-post__id--link:hover { opacity: .82; }
.ff-post__id--link:hover .ff-post__author { color: var(--accent, var(--magenta)); }
.ff-post__avatar { width: 44px; height: 44px; flex: none; border-radius: 50%; overflow: hidden; display: grid; place-items: center; background: linear-gradient(135deg, var(--ink), var(--ink-2)); color: var(--gold-soft); font-family: var(--font-display); font-weight: 700; font-size: 1.1rem; box-shadow: 0 0 0 2px var(--accent, rgba(242,180,12,.3)); transition: box-shadow .2s ease; }
.ff-post__id--link:hover .ff-post__avatar { box-shadow: 0 0 0 3px var(--accent, rgba(242,180,12,.45)); }
.ff-post__avatar img { width: 100%; height: 100%; object-fit: cover; }
.ff-post__idmeta { display: flex; flex-direction: column; min-width: 0; line-height: 1.25; }
.ff-post__author { font-weight: 700; color: var(--ink); font-size: .96rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; transition: color .2s ease; }
.ff-post__time { font-size: .78rem; color: #8a8e9a; }
.ff-post__badge { margin-left: auto; color: var(--emerald); }
.ff-post__badge svg { width: 20px; height: 20px; }
.ff-post__titlelink { text-decoration: none; }
.ff-post__title { font-weight: 600; font-size: clamp(1.2rem, 3.5vw, 1.45rem); line-height: 1.2; letter-spacing: -.01em; color: var(--ink); margin: 0; transition: color .2s ease; }
.ff-post__titlelink:hover .ff-post__title { color: var(--magenta); }
.ff-post__text { margin: .5rem 0 0; color: #4b4f5a; font-size: .96rem; line-height: 1.6; white-space: pre-line; }

.ff-post__media { margin: 1rem 0 0; border-radius: 16px; overflow: hidden; border: 1px solid rgba(13,31,92,.08); background: var(--paper-2); }
.ff-post__media img, .ff-post__media video { display: block; width: 100%; max-height: 460px; object-fit: cover; }
.ff-post__media video { background: #000; }
.ff-post__media--grid { display: grid; grid-template-columns: 1fr 1fr; gap: 3px; background: rgba(13,31,92,.08); }
.ff-post__media--grid img, .ff-post__media--grid video { max-height: 240px; height: 100%; }

.ff-post__metrics { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: .5rem .8rem; margin-top: 1rem; padding-top: .85rem; border-top: 1px solid rgba(13,31,92,.08); font-size: .8rem; color: #8a8e9a; }
.ff-post__react-sum { display: inline-flex; gap: .4rem; }
.ff-react-tally { display: inline-flex; align-items: center; gap: .3em; background: var(--paper); border: 1px solid rgba(13,31,92,.08); border-radius: 999px; padding: .2rem .55rem; font-size: .8rem; font-weight: 600; color: #565a66; }
.ff-react-tally__e { font-size: .92rem; line-height: 1; }
.ff-post__chips { display: inline-flex; align-items: center; gap: .5rem; }
.ff-metric { display: inline-flex; align-items: center; gap: .32em; font-weight: 600; font-size: .8rem; color: #8a8e9a; }
.ff-metric svg { width: 15px; height: 15px; opacity: .75; }
.ff-post__impact { display: inline-flex; align-items: center; gap: .35em; margin-top: .85rem; font-size: .82rem; font-weight: 700; color: var(--accent, var(--ink)); text-decoration: none; transition: gap .2s ease, opacity .2s ease; }
.ff-post__impact svg { width: 15px; height: 15px; transition: transform .2s ease; }
.ff-post__impact:hover { opacity: .82; }
.ff-post__impact:hover svg { transform: translateX(3px); }

.ff-post__actions { display: grid; grid-template-columns: repeat(4, 1fr); gap: .5rem; margin-top: .8rem; }
.ff-react { display: inline-flex; align-items: center; justify-content: center; gap: .35em; border: 1px solid rgba(13,31,92,.12); background: var(--paper); border-radius: 13px; padding: .6rem .3rem; font: inherit; font-weight: 600; font-size: .84rem; color: #4b5563; cursor: pointer; transition: transform .15s ease, background .2s ease, color .2s ease, border-color .2s ease, box-shadow .2s ease; }
.ff-react__emoji { font-size: 1rem; transition: transform .2s ease; }
.ff-react:hover { border-color: rgba(13,31,92,.25); transform: translateY(-1px); }
.ff-react:active .ff-react__emoji { transform: scale(1.35); }
.ff-react--on { color: #fff; border-color: transparent; box-shadow: 0 10px 20px -10px rgba(13,31,92,.5); }
.ff-react--on.ff-react--like { background: linear-gradient(135deg, var(--ink), var(--ink-2)); }
.ff-react--on.ff-react--love { background: linear-gradient(135deg, var(--magenta), #e0567f); box-shadow: 0 10px 20px -10px rgba(193,42,99,.7); }
.ff-react--on.ff-react--support { background: linear-gradient(135deg, var(--emerald), #2bb277); box-shadow: 0 10px 20px -10px rgba(31,138,91,.7); }
.ff-react--open { border-color: var(--gold); box-shadow: 0 0 0 2px rgba(242,180,12,.3); }

.ff-share { display: flex; flex-wrap: wrap; gap: .45rem; margin-top: .6rem; }
.ff-share__chip { border: 1px solid rgba(13,31,92,.14); background: var(--paper); border-radius: 999px; padding: .4rem .85rem; font: inherit; font-size: .78rem; font-weight: 600; color: var(--ink); cursor: pointer; transition: background .2s ease, color .2s ease; }
.ff-share__chip:hover { background: var(--ink); color: var(--paper); }

/* comments */
.ff-post__comments { margin-top: 1rem; padding-top: .9rem; border-top: 1px solid rgba(13,31,92,.08); }
.ff-comments { display: flex; flex-direction: column; gap: .55rem; max-height: 220px; overflow: auto; margin-bottom: .7rem; }
.ff-comment { display: flex; gap: .55rem; }
.ff-comment__avatar { width: 30px; height: 30px; flex: none; border-radius: 50%; display: grid; place-items: center; background: var(--paper-2); color: var(--ink); font-weight: 700; font-size: .78rem; }
.ff-comment__bubble { background: var(--paper); border: 1px solid rgba(13,31,92,.08); border-radius: 14px; padding: .5rem .75rem; }
.ff-comment__name { font-size: .76rem; font-weight: 700; color: var(--ink); margin: 0; }
.ff-comment__text { font-size: .88rem; color: #4b4f5a; margin: .1rem 0 0; }
.ff-commentform { display: flex; gap: .5rem; }
.ff-commentform input { flex: 1; border: 1px solid rgba(13,31,92,.16); border-radius: 999px; padding: .65rem 1rem; font: inherit; font-size: .9rem; background: var(--paper); color: var(--char); }
.ff-commentform input:focus { outline: none; border-color: var(--gold); box-shadow: 0 0 0 3px rgba(242,180,12,.22); }
.ff-commentform__send { flex: none; width: 42px; border: none; border-radius: 999px; background: linear-gradient(135deg, var(--ink), var(--ink-2)); color: #fff; cursor: pointer; display: grid; place-items: center; transition: transform .15s ease; }
.ff-commentform__send:hover { transform: translateY(-1px); }
.ff-commentform__send svg { width: 18px; height: 18px; }

/* ============ MAP ============ */
.ff-mapwrap { display: flex; flex-direction: column; gap: .9rem; }
.ff-maphead { }
.ff-kicker { font-size: .72rem; font-weight: 700; letter-spacing: .16em; text-transform: uppercase; color: var(--magenta); margin: 0 0 .35rem; }
.ff-h2 { font-weight: 600; font-size: clamp(1.4rem, 4vw, 1.9rem); line-height: 1.1; letter-spacing: -.01em; color: var(--ink); margin: 0; }
.ff-maphead__sub { margin: .5rem 0 0; font-size: .9rem; color: #6a6e7a; }
.ff-mapfilters { display: flex; flex-wrap: wrap; gap: .5rem; }
.ff-select { border: 1px solid rgba(13,31,92,.16); background: #fff; border-radius: 13px; padding: .55rem .8rem; font: inherit; font-size: .82rem; font-weight: 500; color: var(--ink); }
.ff-mapcount { font-size: .82rem; color: #6a6e7a; margin: 0; }
.ff-mapcount__hi { color: var(--ink); }
.ff-mapcount__warn { color: #b8860b; }

.ff-ngogrid { display: grid; grid-template-columns: 1fr 1fr; gap: .7rem; }
.ff-ngocard { background: #fffdf6; border: 1px solid rgba(13,31,92,.1); border-radius: 18px; padding: .9rem; box-shadow: 0 12px 30px -24px rgba(13,31,92,.4); }
.ff-ngocard__top { display: flex; align-items: center; gap: .6rem; }
.ff-ngocard__top img { width: 40px; height: 40px; border-radius: 11px; object-fit: cover; background: var(--paper-2); }
.ff-ngocard__name { font-size: .9rem; font-weight: 700; color: var(--ink); margin: 0; }
.ff-ngocard__dist { font-size: .74rem; color: #8a8e9a; margin: 0; }
.ff-ngocard__desc { font-size: .84rem; color: #565a66; margin: .6rem 0 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.ff-ngocard__tags { display: flex; flex-wrap: wrap; gap: .3rem; margin-top: .55rem; }
.ff-ngocard__tags span { font-size: .68rem; font-weight: 600; color: var(--ink); background: rgba(13,31,92,.07); border: 1px solid rgba(13,31,92,.1); padding: .2rem .55rem; border-radius: 999px; }

/* map shell (logic-referenced classes preserved) */
.feeds-map-shell { isolation: isolate; border-radius: 18px; border: 1px solid rgba(13,31,92,.1); background: #fff; box-shadow: 0 14px 34px -26px rgba(13,31,92,.5); }
.feeds-map-canvas { position: relative; z-index: 0; }
.feeds-map-loader { z-index: 20000; background: rgba(247,240,223,.95); backdrop-filter: blur(4px); }
.feeds-map-spinner { border: 3px solid var(--paper-2); border-top-color: var(--gold); animation: feeds-map-spin .75s linear infinite; }
@keyframes feeds-map-spin { to { transform: rotate(360deg); } }

/* ============ LOGIN MODAL ============ */
.ff-authwrap { position: fixed; inset: 0; z-index: 80; display: grid; place-items: center; padding: 20px; background: rgba(8,22,64,.55); backdrop-filter: blur(6px); }
.ff-auth { position: relative; width: 100%; max-width: 380px; text-align: center; background: var(--paper); border-radius: 24px; padding: clamp(28px,5vw,40px) clamp(22px,4vw,34px) clamp(20px,4vw,28px); box-shadow: 0 40px 80px -30px rgba(0,0,0,.6); border: 1px solid rgba(242,180,12,.3); }
.ff-auth__x { position: absolute; top: 14px; right: 16px; background: none; border: none; font-size: 1.05rem; color: #8a8e9a; cursor: pointer; line-height: 1; }
.ff-auth__x:hover { color: var(--ink); }
.ff-auth__emblem { width: 72px; height: 72px; margin: 0 auto 1rem; border-radius: 50%; display: grid; place-items: center; background: radial-gradient(circle at 50% 38%, rgba(242,180,12,.18), rgba(242,180,12,.04) 70%); box-shadow: inset 0 0 0 1px rgba(242,180,12,.35); }
.ff-auth__emblem img { width: 52px; height: auto; }
.ff-auth__title { font-size: 1.6rem; font-weight: 600; color: var(--ink); margin: 0 0 .5rem; }
.ff-auth__text { margin: 0 0 1.4rem; color: #565a66; font-size: .96rem; line-height: 1.55; }
.ff-auth__text strong { color: var(--ink); }
.ff-auth__btns { display: flex; flex-direction: column; gap: .65rem; }
.ff-auth__btns .ff-btn { width: 100%; }
.ff-auth__later { margin-top: .9rem; background: none; border: none; cursor: pointer; font: inherit; font-size: .86rem; color: #8a8e9a; }
.ff-auth__later:hover { color: var(--ink); }

/* transitions */
.ff-fade-enter-active, .ff-fade-leave-active { transition: opacity .2s ease; }
.ff-fade-enter-from, .ff-fade-leave-to { opacity: 0; }
.ff-modal-enter-active, .ff-modal-leave-active { transition: opacity .25s ease; }
.ff-modal-enter-active .ff-auth, .ff-modal-leave-active .ff-auth { transition: transform .3s cubic-bezier(.2,.8,.2,1), opacity .25s ease; }
.ff-modal-enter-from, .ff-modal-leave-to { opacity: 0; }
.ff-modal-enter-from .ff-auth, .ff-modal-leave-to .ff-auth { transform: translateY(16px) scale(.96); opacity: 0; }

@media (prefers-reduced-motion: reduce) {
    .ff-ring--2, .ff-post, .ff-react__emoji, .ff-num--bump { animation: none !important; transition: none !important; }
    .ff-reveal, .ff-reveal.is-revealed { opacity: 1 !important; transform: none !important; transition: none !important; }
}

:deep(.feeds-map-dist-tooltip) {
    border-radius: 0.5rem !important;
    border: 1px solid rgba(13,31,92,.15) !important;
    background: #fff !important;
    box-shadow: 0 4px 12px -2px rgba(13,31,92,.18) !important;
    font-size: 11px !important;
    font-weight: 700 !important;
    color: var(--ink) !important;
    padding: 4px 8px !important;
}
</style>
