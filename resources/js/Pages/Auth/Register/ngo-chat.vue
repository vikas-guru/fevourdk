<template>
    <AppLayout title="NGO Registration - FEVOURD-K">
        <div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50">
            <!-- Loading State -->
            <div v-if="isLoading" class="fixed inset-0 bg-white bg-opacity-90 flex items-center justify-center z-50">
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-robot text-white text-2xl animate-pulse"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">AI Assistant is Loading...</h2>
                    <p class="text-gray-600">Preparing your smart registration experience</p>
                    <div class="flex justify-center space-x-2 mt-4">
                        <div class="w-2 h-2 bg-blue-500 rounded-full animate-bounce"></div>
                        <div class="w-2 h-2 bg-blue-500 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                        <div class="w-2 h-2 bg-blue-500 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                    </div>
                </div>
            </div>

            <!-- Error State -->
            <div v-else-if="hasError" class="min-h-screen flex items-center justify-center">
                <div class="bg-red-50 border border-red-200 rounded-2xl p-8 max-w-md text-center">
                    <i class="fas fa-exclamation-triangle text-red-500 text-4xl mb-4"></i>
                    <h2 class="text-xl font-bold text-red-800 mb-2">Oops! Something went wrong</h2>
                    <p class="text-red-600 mb-4">{{ errorMessage }}</p>
                    <button @click="retryLoading" class="bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 transition-colors">
                        <i class="fas fa-redo mr-2"></i>Try Again
                    </button>
                </div>
            </div>

            <!-- Main Content -->
            <div v-else class="max-w-6xl mx-auto">
                <!-- Stunning Hero Section -->
                <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-blue-600 via-purple-600 to-indigo-700 shadow-2xl mb-8">
                    <!-- Background Pattern -->
                    <div class="absolute inset-0 opacity-10">
                        <div class="absolute top-0 left-0 w-full h-full">
                            <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                                        <path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5"/>
                                    </pattern>
                                </defs>
                                <rect width="100" height="100" fill="url(#grid)" />
                            </svg>
                        </div>
                    </div>
                    
                    <!-- Floating Elements -->
                    <div class="absolute top-4 left-4 w-20 h-20 bg-white opacity-5 rounded-full animate-pulse"></div>
                    <div class="absolute top-12 right-8 w-16 h-16 bg-white opacity-5 rounded-full animate-bounce"></div>
                    <div class="absolute bottom-8 left-12 w-12 h-12 bg-white opacity-5 rounded-full animate-ping"></div>
                    <div class="absolute bottom-4 right-4 w-24 h-24 bg-white opacity-5 rounded-full animate-pulse"></div>
                    
                    <!-- Main Content -->
                    <div class="relative z-10 px-8 py-12 text-center">
                        <!-- Status Badge -->
                        <div class="inline-flex items-center space-x-3 bg-white/20 backdrop-blur-md rounded-full px-6 py-3 mb-6 border border-white/30">
                            <div class="relative">
                                <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
                                <div class="absolute inset-0 w-3 h-3 bg-green-400 rounded-full animate-ping"></div>
                            </div>
                            <span class="text-white font-medium text-sm">🤖 AI Registration Assistant Online</span>
                            <div class="w-2 h-2 bg-yellow-300 rounded-full animate-pulse"></div>
                        </div>
                        
                        <!-- Main Title -->
                        <div class="mb-6">
                            <h1 class="text-5xl md:text-6xl font-bold text-white mb-4 leading-tight">
                                Smart NGO
                                <span class="block bg-gradient-to-r from-yellow-300 to-pink-300 bg-clip-text text-transparent">
                                    Registration 🚀
                                </span>
                            </h1>
                            <p class="text-xl text-white/90 max-w-3xl mx-auto leading-relaxed">
                                Experience the future of NGO registration with our AI-powered assistant. 
                                Complete your registration in just 5 minutes using natural language!
                            </p>
                        </div>
                        
                        <!-- Feature Pills -->
                        <div class="flex flex-wrap justify-center gap-3 mb-8">
                            <div class="bg-white/20 backdrop-blur-md text-white px-4 py-2 rounded-full text-sm font-medium border border-white/30 flex items-center space-x-2 hover:bg-white/30 transition-all">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span>AI-Powered</span>
                            </div>
                            <div class="bg-white/20 backdrop-blur-md text-white px-4 py-2 rounded-full text-sm font-medium border border-white/30 flex items-center space-x-2 hover:bg-white/30 transition-all">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"/>
                                    <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"/>
                                </svg>
                                <span>Natural Language</span>
                            </div>
                            <div class="bg-white/20 backdrop-blur-md text-white px-4 py-2 rounded-full text-sm font-medium border border-white/30 flex items-center space-x-2 hover:bg-white/30 transition-all">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                                </svg>
                                <span>5-Minute Process</span>
                            </div>
                            <div class="bg-white/20 backdrop-blur-md text-white px-4 py-2 rounded-full text-sm font-medium border border-white/30 flex items-center space-x-2 hover:bg-white/30 transition-all">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                                </svg>
                                <span>Expert Support</span>
                            </div>
                        </div>
                        
                        <!-- Stats Section -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 max-w-4xl mx-auto">
                            <div class="bg-white/10 backdrop-blur-md rounded-xl p-4 border border-white/20">
                                <div class="text-3xl font-bold text-white mb-1">500+</div>
                                <div class="text-white/80 text-sm">NGOs Registered</div>
                            </div>
                            <div class="bg-white/10 backdrop-blur-md rounded-xl p-4 border border-white/20">
                                <div class="text-3xl font-bold text-white mb-1">98%</div>
                                <div class="text-white/80 text-sm">Success Rate</div>
                            </div>
                            <div class="bg-white/10 backdrop-blur-md rounded-xl p-4 border border-white/20">
                                <div class="text-3xl font-bold text-white mb-1">5min</div>
                                <div class="text-white/80 text-sm">Avg. Time</div>
                            </div>
                            <div class="bg-white/10 backdrop-blur-md rounded-xl p-4 border border-white/20">
                                <div class="text-3xl font-bold text-white mb-1">24/7</div>
                                <div class="text-white/80 text-sm">AI Available</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Chat Container -->
                <div class="bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-100">
                    <!-- Enhanced Progress Bar -->
                    <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 h-3 relative">
                        <div 
                            class="bg-white h-full transition-all duration-700 ease-out relative shadow-lg"
                            :style="`width: ${progressPercentage}%`"
                        >
                            <div class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-xs px-2 py-1 rounded-full shadow-md">
                                {{ Math.round(progressPercentage) }}%
                            </div>
                        </div>
                    </div>

                    <!-- AI Status Bar -->
                    <div class="bg-gradient-to-r from-blue-50 to-purple-50 px-4 py-2 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <div class="relative">
                                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                    <div class="absolute inset-0 w-2 h-2 bg-green-500 rounded-full animate-ping"></div>
                                </div>
                                <span class="text-sm font-medium text-gray-700">🤖 AI Assistant Online</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <span class="text-xs text-gray-500">Natural Language Processing</span>
                                <div class="flex space-x-1">
                                    <div class="w-1 h-1 bg-blue-500 rounded-full animate-bounce"></div>
                                    <div class="w-1 h-1 bg-purple-500 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                                    <div class="w-1 h-1 bg-indigo-500 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Smart Suggestions Bar -->
                    <div v-if="suggestions.length > 0" class="bg-gradient-to-r from-blue-50 to-purple-50 p-3 border-b border-gray-200">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-lightbulb text-yellow-500"></i>
                            <span class="text-sm text-gray-700">💡 Smart suggestions:</span>
                        </div>
                        <div class="flex flex-wrap gap-2 mt-2">
                            <button 
                                v-for="suggestion in suggestions"
                                :key="suggestion"
                                @click="useSuggestion(suggestion)"
                                class="bg-white border border-gray-300 rounded-full px-3 py-1 text-sm hover:bg-blue-50 hover:border-blue-300 transition-all transform hover:scale-105"
                            >
                                {{ suggestion }}
                            </button>
                        </div>
                    </div>

                    <!-- Enhanced Chat Messages -->
                    <div class="h-96 overflow-y-auto p-6 space-y-4 bg-gradient-to-b from-gray-50 to-white" ref="chatContainer">
                        <!-- Welcome Message -->
                        <div class="flex items-start space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center flex-shrink-0 shadow-lg">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"/>
                                    <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"/>
                                </svg>
                            </div>
                            <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl rounded-tl-none p-4 max-w-md shadow-md border border-blue-100">
                                <p class="text-gray-800 font-medium">Hello! 👋 I'm your AI registration assistant. I can understand natural language and help you register your NGO in just 5 minutes!</p>
                                <p class="text-gray-800 mt-2">You can tell me about your NGO in your own words, or I can guide you step by step. What would you prefer?</p>
                            </div>
                        </div>

                        <!-- User Messages -->
                        <div v-for="(message, index) in conversation" :key="index" class="space-y-4">
                            <!-- User Message -->
                            <div v-if="message.type === 'user'" class="flex items-start space-x-3 justify-end">
                                <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-2xl rounded-tr-none p-4 max-w-md shadow-lg">
                                    <p class="font-medium">{{ message.text }}</p>
                                    <div v-if="message.entities" class="mt-2 text-xs opacity-75">
                                        <span v-for="entity in message.entities" :key="entity.type" class="mr-2">
                                            🏷️ {{ entity.type }}: {{ entity.value }}
                                        </span>
                                    </div>
                                </div>
                                <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center flex-shrink-0 shadow-md">
                                    <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                                    </svg>
                                </div>
                            </div>

                            <!-- Assistant Message -->
                            <div v-if="message.type === 'assistant'" class="flex items-start space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center flex-shrink-0 shadow-lg">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"/>
                                        <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"/>
                                    </svg>
                                </div>
                                <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl rounded-tl-none p-4 max-w-md shadow-md border border-blue-100">
                                    <p class="text-gray-800 font-medium" v-html="message.text"></p>
                                    
                                    <!-- Smart Summary -->
                                    <div v-if="message.summary" class="mt-3 bg-blue-50 rounded-lg p-3 border border-blue-200">
                                        <div class="flex items-center space-x-2 mb-2">
                                            <i class="fas fa-clipboard-check text-blue-600"></i>
                                            <span class="text-sm font-medium text-blue-800">🎯 I understood:</span>
                                        </div>
                                        <ul class="text-sm text-blue-700 space-y-1">
                                            <li v-for="item in message.summary" :key="item">• {{ item }}</li>
                                        </ul>
                                    </div>

                                    <!-- Smart Fields Extraction -->
                                    <div v-if="message.extractedFields" class="mt-4 bg-green-50 rounded-lg p-3 border border-green-200">
                                        <div class="flex items-center space-x-2 mb-2">
                                            <i class="fas fa-magic text-green-600"></i>
                                            <span class="text-sm font-medium text-green-800">🔮 Extracted:</span>
                                        </div>
                                        <div class="grid grid-cols-1 gap-2 text-sm">
                                            <div v-for="(value, field) in message.extractedFields" :key="field" class="flex justify-between">
                                                <span class="text-gray-600">{{ formatFieldName(field) }}:</span>
                                                <span class="font-medium text-green-700">{{ value }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Input Field -->
                                    <div v-if="message.input" class="mt-4">
                                        <input 
                                            v-model="currentInput"
                                            :type="message.input.type"
                                            :placeholder="message.input.placeholder"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            @keypress.enter="submitInput"
                                            @input="handleSmartInput"
                                            :disabled="isProcessing"
                                        >
                                        <div v-if="smartHints.length > 0" class="mt-2 bg-yellow-50 rounded p-2">
                                            <p class="text-xs text-yellow-800 mb-1">💡 Smart hints detected:</p>
                                            <div class="flex flex-wrap gap-1">
                                                <span v-for="hint in smartHints" :key="hint" class="bg-yellow-200 text-yellow-800 px-2 py-1 rounded text-xs">
                                                    {{ hint }}
                                                </span>
                                            </div>
                                        </div>
                                        <button 
                                            @click="submitInput"
                                            :disabled="!currentInput.trim() || isProcessing"
                                            class="mt-3 bg-gradient-to-r from-blue-500 to-purple-500 text-white px-6 py-3 rounded-lg font-medium hover:from-blue-600 hover:to-purple-600 disabled:opacity-50 disabled:cursor-not-allowed transition-all transform hover:scale-105"
                                        >
                                            <span v-if="isProcessing">
                                                <i class="fas fa-spinner fa-spin mr-2"></i>AI Processing...
                                            </span>
                                            <span v-else>
                                                <i class="fas fa-paper-plane mr-2"></i>Send
                                            </span>
                                        </button>
                                    </div>

                                    <!-- Options -->
                                    <div v-if="message.options" class="mt-4 space-y-2">
                                        <button 
                                            v-for="option in message.options"
                                            :key="option.value"
                                            @click="selectOption(option)"
                                            :disabled="isProcessing"
                                            class="w-full text-left p-3 bg-white border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-300 disabled:opacity-50 disabled:cursor-not-allowed transition-all transform hover:scale-105"
                                        >
                                            {{ option.label }}
                                        </button>
                                    </div>

                                    <!-- File Upload -->
                                    <div v-if="message.upload" class="mt-4">
                                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-gray-400 transition-colors">
                                            <div v-if="!uploadedFiles[message.upload.type]" class="space-y-2">
                                                <i class="fas fa-cloud-upload-alt text-3xl text-gray-400"></i>
                                                <p class="text-sm text-gray-600">Drag & drop or click to upload</p>
                                                <p class="text-xs text-gray-500">{{ message.upload.accept }}</p>
                                                <div class="bg-blue-50 rounded-lg p-2 mt-2">
                                                    <p class="text-xs text-blue-700">🤖 AI will automatically extract data from your documents!</p>
                                                </div>
                                                <input 
                                                    type="file" 
                                                    :accept="message.upload.accept"
                                                    @change="handleFileUpload(message.upload.type, $event)"
                                                    class="hidden"
                                                    :id="`file-${message.upload.type}`"
                                                >
                                                <label :for="`file-${message.upload.type}`" class="cursor-pointer">
                                                    <span class="bg-blue-100 text-blue-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-200 transition-colors">
                                                        Choose File
                                                    </span>
                                                </label>
                                            </div>
                                            <div v-else class="flex items-center justify-between">
                                                <div class="flex items-center space-x-2">
                                                    <i class="fas fa-check-circle text-green-500"></i>
                                                    <span class="text-sm text-gray-700">{{ uploadedFiles[message.upload.type].name }}</span>
                                                </div>
                                                <button @click="removeFile(message.upload.type)" class="text-red-500 hover:text-red-700 text-sm">
                                                    Remove
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Typing Indicator -->
                            <div v-if="isTyping" class="flex items-start space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center flex-shrink-0 shadow-lg">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"/>
                                        <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"/>
                                    </svg>
                                </div>
                                <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl rounded-tl-none p-4 shadow-md border border-blue-100">
                                    <div class="flex items-center space-x-2">
                                        <div class="flex space-x-1">
                                            <div class="w-2 h-2 bg-blue-500 rounded-full animate-bounce"></div>
                                            <div class="w-2 h-2 bg-purple-500 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                                            <div class="w-2 h-2 bg-indigo-500 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                                        </div>
                                        <span class="text-sm text-gray-600 ml-2 font-medium">🤖 AI is thinking...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Smart Input Area -->
                    <div class="border-t border-gray-200 p-4 bg-gradient-to-r from-gray-50 to-blue-50">
                        <div class="flex items-center space-x-3">
                            <div class="flex-1 relative">
                                <input 
                                    v-model="currentInput"
                                    type="text"
                                    placeholder="Type your message or ask me anything..."
                                    class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    @keypress.enter="submitInput"
                                    @input="handleSmartInput"
                                    :disabled="isProcessing"
                                >
                                <button 
                                    @click="submitInput"
                                    :disabled="!currentInput.trim() || isProcessing"
                                    class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-blue-500 text-white p-2 rounded-lg hover:bg-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-all transform hover:scale-105"
                                >
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/>
                                    </svg>
                                </button>
                            </div>
                            
                            <!-- Enhanced Voice Input Button -->
                            <button 
                                @click="startVoiceInput"
                                :disabled="isVoiceActive"
                                class="bg-blue-500 text-white p-3 rounded-lg hover:bg-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-all transform hover:scale-105"
                                :title="isVoiceActive ? 'Stop recording' : 'Start voice input'"
                            >
                                <svg v-if="!isVoiceActive" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                    <path d="M10 4a4 4 0 00-4 4v2a4 4 0 008 0V8a4 4 0 00-4-4zM3.5 9a6.5 6.5 0 0113 0v.5a.5.5 0 001 0V9a7.5 7.5 0 00-15 0v.5a.5.5 0 001 0V9z"/>
                                </svg>
                                <svg v-else class="w-5 h-5 text-red-200" fill="currentColor" viewBox="0 0 20 20">
                                    <rect x="6" y="6" width="8" height="8"/>
                                </svg>
                            </button>
                            
                            <!-- Enhanced Speech Controls -->
                            <div class="flex items-center space-x-1">
                                <!-- Play/Resume Button -->
                                <button 
                                    v-if="!isSpeaking || isPaused"
                                    @click="isPaused ? resumeSpeech() : speakText('Hello! I am your AI registration assistant. I can understand natural language and help you register your NGO in just 5 minutes!')"
                                    :disabled="isProcessing"
                                    class="bg-green-500 text-white p-3 rounded-lg hover:bg-green-600 disabled:opacity-50 disabled:cursor-not-allowed transition-all transform hover:scale-105"
                                    :title="isPaused ? 'Resume speech' : 'Play welcome message'"
                                >
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M5 4v12l10-6z"/>
                                    </svg>
                                </button>
                                
                                <!-- Pause Button -->
                                <button 
                                    v-if="isSpeaking && !isPaused"
                                    @click="pauseSpeech"
                                    class="bg-yellow-500 text-white p-3 rounded-lg hover:bg-yellow-600 disabled:opacity-50 disabled:cursor-not-allowed transition-all transform hover:scale-105"
                                    title="Pause speech"
                                >
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M5 4h3v12H5V4zm7 0h3v12h-3V4z"/>
                                    </svg>
                                </button>
                                
                                <!-- Stop Button -->
                                <button 
                                    v-if="isSpeaking"
                                    @click="stopSpeaking"
                                    class="bg-red-500 text-white p-3 rounded-lg hover:bg-red-600 disabled:opacity-50 disabled:cursor-not-allowed transition-all transform hover:scale-105"
                                    title="Stop speech"
                                >
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <rect x="5" y="5" width="10" height="10"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Enhanced Quick Actions -->
                        <div class="flex items-center justify-between mt-4">
                            <div class="flex flex-wrap gap-2">
                                <button 
                                    @click="saveProgress"
                                    class="bg-white text-gray-700 px-3 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition-all border border-gray-200 flex items-center space-x-2"
                                >
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>
                                    </svg>
                                    <span>Save Progress</span>
                                </button>
                                <button 
                                    @click="getHelp"
                                    class="bg-white text-gray-700 px-3 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition-all border border-gray-200 flex items-center space-x-2"
                                >
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"/>
                                    </svg>
                                    <span>Get Help</span>
                                </button>
                                <button 
                                    @click="toggleAutoFill"
                                    :class="autoFillEnabled ? 'bg-blue-500 text-white' : 'bg-white text-gray-700'"
                                    class="px-3 py-2 rounded-lg text-sm font-medium hover:bg-blue-600 transition-all border border-gray-200 flex items-center space-x-2"
                                >
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z"/>
                                    </svg>
                                    <span>{{ autoFillEnabled ? 'Auto-fill ON' : 'Auto-fill OFF' }}</span>
                                </button>
                            </div>
                            
                            <!-- Progress Indicator -->
                            <div class="flex items-center space-x-3">
                                <div class="text-sm text-gray-600">
                                    <span class="font-medium">{{ completedSteps }}</span> of <span class="font-medium">{{ totalSteps }}</span> steps
                                </div>
                                <div class="w-24 bg-gray-200 rounded-full h-2">
                                    <div 
                                        class="bg-gradient-to-r from-blue-500 to-purple-500 h-2 rounded-full transition-all duration-500"
                                        :style="`width: ${progressPercentage}%`"
                                    ></div>
                                </div>
                                <div class="text-sm font-medium text-gray-700">
                                    {{ Math.round(progressPercentage) }}%
                                </div>
                            </div>
                        </div>
                        
                        <!-- Enhanced Smart Tips -->
                        <div class="mt-3 bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg p-4 border border-blue-200">
                            <div class="flex items-start space-x-3">
                                <div class="bg-blue-500 text-white p-2 rounded-full">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <span class="text-sm text-blue-800 font-bold">💡 Pro Tips:</span>
                                    <ul class="text-sm text-blue-700 mt-1 space-y-1">
                                        <li>• Try saying "We help educate children in Bangalore"</li>
                                        <li>• Click the microphone to speak naturally</li>
                                        <li>• Use play button to hear instructions again</li>
                                        <li>• AI will automatically extract information from your responses</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Help Modal -->
                <div v-if="showHelpModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
                    <div class="bg-white rounded-2xl p-6 max-w-md w-full">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">🤖 AI Assistant Help</h3>
                        <div class="space-y-3">
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-brain text-blue-500 mt-1"></i>
                                <div>
                                    <p class="font-medium">Natural Language</p>
                                    <p class="text-sm text-gray-600">Type naturally: "We help educate children in Bangalore"</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-magic text-purple-500 mt-1"></i>
                                <div>
                                    <p class="font-medium">Smart Extraction</p>
                                    <p class="text-sm text-gray-600">AI automatically extracts info from your text</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-microphone text-green-500 mt-1"></i>
                                <div>
                                    <p class="font-medium">Voice Input</p>
                                    <p class="text-sm text-gray-600">Click mic to speak your responses</p>
                                </div>
                            </div>
                        </div>
                        <button @click="showHelpModal = false" class="mt-6 w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            Got it!
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, reactive, computed, nextTick, onMounted } from 'vue'
import { useForm, Link, router } from '@inertiajs/vue3'

const chatContainer = ref(null)
const currentInput = ref('')
const isTyping = ref(false)
const isProcessing = ref(false)
const showHelpModal = ref(false)
const uploadedFiles = ref({})
const currentStep = ref(0)
const suggestions = ref([])
const smartHints = ref([])
const isVoiceActive = ref(false)
const autoFillEnabled = ref(false)
const recognition = ref(null)
const speechSynthesis = window.speechSynthesis
const isSpeaking = ref(false)
const isPaused = ref(false)
const currentUtterance = ref(null)
const isLoading = ref(true)
const hasError = ref(false)
const errorMessage = ref('')



const conversation = ref([
    {
        type: 'assistant',
        text: 'Hello! 👋 I\'m your AI registration assistant. I can understand natural language and help you register your NGO in just 5 minutes!<br><br>You can tell me about your NGO in your own words, or I can guide you step by step. What would you prefer?',
        options: [
            { label: '🤖 Guide me step by step', value: 'guided' },
            { label: '💬 Tell you about my NGO', value: 'natural' },
            { label: '📋 Fill traditional form', value: 'form' }
        ]
    }
])

const formData = reactive({
    ngo_name: '',
    registration_number: '',
    pan: '',
    email: '',
    phone: '',
    address: '',
    city: '',
    description: '',
    focus_areas: [],
    logo: null,
    documents: {}
})

// AI-powered natural language processing patterns
const entityPatterns = {
    ngo_name: [
        /(?:we are|my ngo is|our organization is|ngo name is|called|named?)\s*([A-Za-z0-9\s&]+)/gi,
        /(?:register|registering)\s+([A-Za-z0-9\s&]+)/gi,
        /([A-Za-z0-9\s&]+(?:trust|foundation|society|ngo|organization))/gi
    ],
    registration_number: [
        /(?:registration\s+no|reg\s+no|registration\s+number|reg\s+number)[:\s]*([A-Za-z0-9\/-]+)/gi,
        /(?:no\.|num\.?)\s*([A-Za-z0-9\/-]+)/gi
    ],
    pan: [
        /(?:pan\s+no|pan\s+number|pan)[:\s]*([A-Za-z]{5}[0-9]{4})/gi,
        /\b([A-Za-z]{5}[0-9]{4})\b/g
    ],
    email: [
        /(?:email|mail|contact)\s*[:@]?\s*([a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})/gi,
        /\b([a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})\b/g
    ],
    phone: [
        /(?:phone|mobile|contact|call)\s*[:+]?\s*([0-9+\s-]{10,15})/gi,
        /\b([0-9+\s-]{10,15})\b/g
    ],
    city: [
        /(?:based\s+in|located\s+in|city|from)\s+([A-Za-z\s]+)/gi,
        /(?:bangalore|bengaluru|mysore|hubli|mangalore|belgaum|gulbarga|bidar|raichur|koppal|bellary|chitradurga|davanagere|chikmagalur|shimoga|udupi|dakshina\s+kannada|uttara\s+kannada|haveri|gadag|kolar|chikkaballapur|ramanagara|mandya|hassan|kodagu|tumkur|chamarajanagar|yadgir|vijayanagara)/gi
    ],
    focus_areas: [
        /(?:education|educate|teaching|school|learning)/gi,
        /(?:health|medical|hospital|healthcare|medicine)/gi,
        /(?:environment|environmental|eco|green|climate|sustainability)/gi,
        /(?:women|female|girl|gender|empowerment)/gi,
        /(?:child|children|kids|youth|student)/gi,
        /(?:rural|village|farm|agriculture|development)/gi
    ]
}

const progressPercentage = computed(() => {
    return Math.round((currentStep.value / 10) * 100)
})

const completedSteps = computed(() => {
    return Object.values(formData).filter(value => 
        value !== '' && value !== null && (!Array.isArray(value) || value.length > 0)
    ).length
})

const totalSteps = computed(() => 10)

// AI-powered natural language processing
const processNaturalLanguage = (text) => {
    const extracted = {}
    const entities = []
    
    // Extract entities using patterns
    for (const [field, patterns] of Object.entries(entityPatterns)) {
        for (const pattern of patterns) {
            const matches = text.match(pattern)
            if (matches) {
                extracted[field] = matches[matches.length - 1] || matches[0]
                entities.push({ type: field, value: extracted[field] })
                break
            }
        }
    }
    
    return { extracted, entities }
}

const generateSmartSuggestions = (context) => {
    const suggestionMap = {
        ngo_name: [
            'Children Education Trust',
            'Women Empowerment Society',
            'Rural Development Foundation',
            'Healthcare Initiative',
            'Environmental Conservation NGO'
        ],
        city: [
            'Bangalore', 'Mysore', 'Hubli', 'Mangalore', 'Belgaum',
            'Gulbarga', 'Bidar', 'Raichur', 'Koppal', 'Bellary'
        ],
        focus_areas: [
            'Education & Skill Development',
            'Healthcare & Sanitation',
            'Women & Child Welfare',
            'Environmental Conservation',
            'Rural Development'
        ]
    }
    
    return suggestionMap[context] || []
}

const handleSmartInput = (event) => {
    const text = event.target.value.toLowerCase()
    
    // Generate smart hints based on input
    if (text.includes('bangalore') || text.includes('bengaluru')) {
        smartHints.value = ['Karnataka', 'Urban', 'IT Hub']
    }
    if (text.includes('education') || text.includes('school')) {
        smartHints.value = ['Non-profit', 'Learning', 'Children']
    }
    if (text.includes('trust') || text.includes('society')) {
        smartHints.value = ['Registered', 'Legal entity', 'Non-profit']
    }
    
    // Generate suggestions
    if (text.length > 3) {
        if (currentStep.value === 0) {
            suggestions.value = generateSmartSuggestions('ngo_name').filter(s => 
                s.toLowerCase().includes(text)
            ).slice(0, 3)
        }
    }
}

const useSuggestion = (suggestion) => {
    currentInput.value = suggestion
    suggestions.value = []
    smartHints.value = []
}

const startVoiceInput = () => {
    if (!('webkitSpeechRecognition' in window)) {
        alert('Voice input is not supported in your browser')
        return
    }
    
    if (!recognition.value) {
        recognition.value = new webkitSpeechRecognition()
        recognition.value.continuous = false
        recognition.value.interimResults = false
        recognition.value.lang = 'en-US'
        
        recognition.value.onresult = (event) => {
            const transcript = event.results[0][0].transcript
            currentInput.value = transcript
            isVoiceActive.value = false
        }
        
        recognition.value.onerror = () => {
            isVoiceActive.value = false
        }
    }
    
    if (isVoiceActive.value) {
        recognition.value.stop()
        isVoiceActive.value = false
    } else {
        recognition.value.start()
        isVoiceActive.value = true
    }
}

const speakText = (text) => {
    if (!speechSynthesis) return
    
    // Cancel any ongoing speech
    speechSynthesis.cancel()
    
    // Clean up HTML tags for speech
    const cleanText = text.replace(/<[^>]*>/g, '').replace(/&nbsp;/g, ' ')
    
    currentUtterance.value = new SpeechSynthesisUtterance(cleanText)
    currentUtterance.value.rate = 0.9
    currentUtterance.value.pitch = 1.0
    currentUtterance.value.volume = 0.8
    
    currentUtterance.value.onstart = () => {
        isSpeaking.value = true
        isPaused.value = false
    }
    
    currentUtterance.value.onend = () => {
        isSpeaking.value = false
        isPaused.value = false
        currentUtterance.value = null
    }
    
    currentUtterance.value.onerror = () => {
        isSpeaking.value = false
        isPaused.value = false
        currentUtterance.value = null
    }
    
    speechSynthesis.speak(currentUtterance.value)
}

const pauseSpeech = () => {
    if (speechSynthesis && isSpeaking.value && !isPaused.value) {
        speechSynthesis.pause()
        isPaused.value = true
    }
}

const resumeSpeech = () => {
    if (speechSynthesis && isSpeaking.value && isPaused.value) {
        speechSynthesis.resume()
        isPaused.value = false
    }
}

const stopSpeaking = () => {
    if (speechSynthesis) {
        speechSynthesis.cancel()
        isSpeaking.value = false
        isPaused.value = false
        currentUtterance.value = null
    }
}

const toggleAutoFill = () => {
    autoFillEnabled.value = !autoFillEnabled.value
    if (autoFillEnabled.value) {
        // Auto-fill with demo data
        currentInput.value = 'We are the Children Education Trust based in Bangalore, working to provide quality education to underprivileged children.'
        setTimeout(() => submitInput(), 1000)
    }
}

const formatFieldName = (field) => {
    const fieldNames = {
        ngo_name: 'NGO Name',
        registration_number: 'Registration No',
        pan: 'PAN Number',
        email: 'Email Address',
        phone: 'Phone Number',
        address: 'Address',
        city: 'City',
        description: 'Description',
        focus_areas: 'Focus Areas'
    }
    return fieldNames[field] || field
}

const submitInput = async () => {
    if (!currentInput.value.trim()) return
    
    const { extracted, entities } = processNaturalLanguage(currentInput.value)
    
    // Add user message with entities
    conversation.value.push({
        type: 'user',
        text: currentInput.value,
        entities: entities
    })
    
    // Update form data with extracted information
    Object.assign(formData, extracted)
    
    currentInput.value = ''
    isProcessing.value = true
    suggestions.value = []
    smartHints.value = []
    
    // Show typing indicator
    isTyping.value = true
    await new Promise(resolve => setTimeout(resolve, 1500))
    isTyping.value = false
    
    // Generate smart response
    await generateSmartResponse(extracted)
    isProcessing.value = false
    
    scrollToBottom()
}

const generateSmartResponse = async (extracted) => {
    let responseText = ''
    let summary = []
    let extractedFields = {}
    let inputConfig = null
    let options = null
    
    // Create summary of what was understood
    if (extracted.ngo_name) {
        summary.push(`NGO Name: ${extracted.ngo_name}`)
        extractedFields.ngo_name = extracted.ngo_name
    }
    if (extracted.city) {
        summary.push(`Location: ${extracted.city}`)
        extractedFields.city = extracted.city
    }
    if (extracted.focus_areas) {
        summary.push(`Focus: Education & Development`)
        extractedFields.focus_areas = ['education', 'child_welfare']
    }
    
    // Generate contextual response
    if (Object.keys(extracted).length > 0) {
        responseText = `🎯 Excellent! I understood the following information:<br><br>`
        
        if (extracted.ngo_name) {
            responseText += `• NGO Name: <strong>${extracted.ngo_name}</strong><br>`
            currentStep.value = 1
        }
        if (extracted.city) {
            responseText += `• Location: <strong>${extracted.city}</strong><br>`
            currentStep.value = 2
        }
        if (extracted.focus_areas) {
            responseText += `• Focus Areas: Education & Development<br>`
            currentStep.value = 3
        }
        
        responseText += `<br><br>Let me help you complete the registration. What's your official registration number?`
        inputConfig = { type: 'text', placeholder: 'Enter registration number' }
        
    } else {
        responseText = 'I\'d love to help! Could you tell me more about your NGO? For example:<br><br>• "We are the Children Education Trust in Bangalore"<br>• "My NGO works on women empowerment in Mysore"<br>• "We focus on environmental conservation across Karnataka"'
        suggestions.value = generateSmartSuggestions('ngo_name')
    }
    
    conversation.value.push({
        type: 'assistant',
        text: responseText,
        summary: summary.length > 0 ? summary : null,
        extractedFields: Object.keys(extractedFields).length > 0 ? extractedFields : null,
        input: inputConfig,
        options: options
    })
}

const selectOption = async (option) => {
    if (option.value === 'guided') {
        // Switch to guided mode
        conversation.value.push({
            type: 'assistant',
            text: 'Great choice! I\'ll guide you step by step. Let\'s start with your NGO name.',
            input: { type: 'text', placeholder: 'Enter your NGO name' }
        })
        speakText('Great choice! I will guide you step by step. Let us start with your NGO name.')
    } else if (option.value === 'form') {
        // Redirect to traditional form
        window.location.href = '/register/ngo'
        return
    } else if (option.value === 'natural') {
        conversation.value.push({
            type: 'assistant',
            text: 'Perfect! Tell me about your NGO in your own words. You can say something like:<br><br>• "We are the Children Education Trust in Bangalore"<br>• "My NGO works on women empowerment in Mysore"<br>• "We focus on environmental conservation across Karnataka"<br><br>Go ahead, I\'m listening! 🎧'
        })
        speakText('Perfect! Tell me about your NGO in your own words. Go ahead, I am listening!')
    }
    
    scrollToBottom()
}

const handleFileUpload = (type, event) => {
    const file = event.target.files[0]
    if (file) {
        uploadedFiles.value[type] = file
        formData[type] = file
        
        // Simulate AI extraction from document
        conversation.value.push({
            type: 'assistant',
            text: `📄 I've received your ${file.name}. Let me extract the information...`,
            extractedFields: {
                'Document Type': file.type.includes('pdf') ? 'PDF Document' : 'Image File',
                'File Size': (file.size / 1024).toFixed(2) + ' KB',
                'Status': 'Processing...'
            }
        })
        
        setTimeout(() => {
            conversation.value.push({
                type: 'assistant',
                text: `✅ Successfully extracted information from ${file.name}! The document has been added to your registration.`,
                summary: ['Document uploaded and processed']
            })
        }, 2000)
        
        scrollToBottom()
    }
}

const removeFile = (type) => {
    uploadedFiles.value[type] = null
    formData[type] = null
}

const saveProgress = () => {
    localStorage.setItem('ngo_registration_progress', JSON.stringify({
        formData,
        currentStep: currentStep.value,
        conversation: conversation.value
    }))
    
    conversation.value.push({
        type: 'assistant',
        text: '✅ Your progress has been saved! I\'ll remember everything when you come back.'
    })
    scrollToBottom()
}

const getHelp = () => {
    showHelpModal.value = true
}

const retryLoading = () => {
    isLoading.value = true
    hasError.value = false
    errorMessage.value = ''
    
    // Simulate loading and then show main interface
    setTimeout(() => {
        isLoading.value = false
    }, 2000)
}

const scrollToBottom = () => {
    nextTick(() => {
        if (chatContainer.value) {
            chatContainer.value.scrollTop = chatContainer.value.scrollHeight
        }
    })
}

onMounted(() => {
    // Simulate initial loading
    setTimeout(() => {
        isLoading.value = false
        // Auto-speak welcome message when screen loads
        setTimeout(() => {
            speakText('Hello! I am your AI registration assistant. I can understand natural language and help you register your NGO in just 5 minutes! You can tell me about your NGO in your own words, or I can guide you step by step. What would you prefer?')
        }, 500)
    }, 1500)
    
    // Load saved progress if exists
    const savedProgress = localStorage.getItem('ngo_registration_progress')
    if (savedProgress) {
        const progress = JSON.parse(savedProgress)
        Object.assign(formData, progress.formData)
        currentStep.value = progress.currentStep
        conversation.value = progress.conversation
    }
    
    scrollToBottom()
})
</script>

<style scoped>
@keyframes bounce {
    0%, 80%, 100% {
        transform: scale(0);
    }
    40% {
        transform: scale(1.0);
    }
}

.animate-bounce {
    animation: bounce 1.4s infinite;
}

/* Custom scrollbar */
.overflow-y-auto::-webkit-scrollbar {
    width: 6px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 10px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>
