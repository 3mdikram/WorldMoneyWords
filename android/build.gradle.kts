plugins {
    id("com.android.library") version "8.7.3"
    id("org.jetbrains.kotlin.android") version "2.0.21"
    id("maven-publish")
}

group = "io.github.3mdikram"
version = "0.0.0-SNAPSHOT"

android {
    namespace = "io.github.mdikram.worldmoneywords.android"
    compileSdk = 35

    defaultConfig {
        minSdk = 21
        consumerProguardFiles("consumer-rules.pro")
        testInstrumentationRunner = "androidx.test.runner.AndroidJUnitRunner"
    }

    buildTypes {
        release {
            isMinifyEnabled = false
        }
    }

    compileOptions {
        sourceCompatibility = JavaVersion.VERSION_11
        targetCompatibility = JavaVersion.VERSION_11
    }

    kotlinOptions {
        jvmTarget = "11"
    }

    publishing {
        singleVariant("release") {
            withSourcesJar()
        }
    }
}

dependencies {
    api("io.github.3mdikram:worldmoneywords:2.5.0")
    testImplementation("junit:junit:4.13.2")
}

afterEvaluate {
    publishing {
        publications {
            create<MavenPublication>("release") {
                from(components["release"])
                artifactId = "worldmoneywords-android"
                pom {
                    name.set("WorldMoneyWords for Android")
                    description.set("Kotlin-friendly Android amount-to-words and currency wording library")
                    url.set("https://github.com/3mdikram/WorldMoneyWords")
                    licenses {
                        license {
                            name.set("MIT License")
                            url.set("https://opensource.org/licenses/MIT")
                        }
                    }
                    developers {
                        developer {
                            name.set("Md Ikram")
                            url.set("https://github.com/3mdikram")
                        }
                    }
                    scm {
                        connection.set("scm:git:git://github.com/3mdikram/WorldMoneyWords.git")
                        developerConnection.set("scm:git:ssh://github.com/3mdikram/WorldMoneyWords.git")
                        url.set("https://github.com/3mdikram/WorldMoneyWords/tree/main")
                    }
                }
            }
        }
    }
}
