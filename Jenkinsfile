pipeline {
agent {
      node {
      label 'master'
    }
}

stages { 
    stage ('Git Pull Automations fo Sterio Laravel Projects ') {
        steps {
          sh """
          sudo ansible-playbook /home/ubuntu/ansible/sterio_develop.yml -vv
          """
        }
    }
}

post {

         success {
         emailext  attachLog: "true", body: '''${SCRIPT, template="groovy-html.template"}''',mimeType: 'text/html',subject: "Jenkins Build ${currentBuild.currentResult}: Job ${env.JOB_NAME}",to: "jignesh@topsinfosolutions.com,sonal@topsinfosolutions.com,juber@topsinfosolutions.com"
         }
         failure {
        
          emailext  attachLog: "true", body: '''${SCRIPT, template="groovy-html.template"}''',mimeType: 'text/html',subject: "Jenkins Build ${currentBuild.currentResult}: Job ${env.JOB_NAME}",to: "jignesh@topsinfosolutions.com,sonal@topsinfosolutions.com,juber@topsinfosolutions.com"
        }

    }
}
